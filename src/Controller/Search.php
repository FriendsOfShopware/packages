<?php

namespace App\Controller;

use App\Command\PackageIndexerCommand;
use Elasticsearch\Client;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\NestedAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchPhraseQuery;
use ONGR\ElasticsearchDSL\Query\Joining\NestedQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\FuzzyQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\WildcardQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Search extends AbstractController
{
    /**
     * @Route(path="/search", name="search")
     */
    public function search(): Response
    {
        return $this->render('search.html.twig');
    }

    /**
     * @Route(path="/api/search", name="api_search")
     */
    public function ajaxSearch(Request $request, Client $client): Response
    {
        $search = $this->buildQuery($request)->toArray();

        $searchResult = $client->search([
            'index' => PackageIndexerCommand::INDEX_NAME,
            'body' => $search,
        ]);

        return $this->render('ajax/search.html.twig', [
            'searchResult' => $searchResult,
            'selectedTypes' => explode('|', $request->query->get('types', '')),
            'selectedProducers' => explode('|', $request->query->get('producers', '')),
        ]);
    }

    private function buildQuery(Request $request): \ONGR\ElasticsearchDSL\Search
    {
        $search = new \ONGR\ElasticsearchDSL\Search();

        $producerAggregation = new TermsAggregation('producers');
        $producerAggregation->setField('producerName.raw');
        $producerAggregation->addParameter('size', 20);
        $search->addAggregation($producerAggregation);
        $search->setSize(100);

        $typeAggregation = new TermsAggregation('aggs');
        $typeAggregation->setField('types.name');

        $nestedAggregation = new NestedAggregation('types', 'types');
        $nestedAggregation->addAggregation($typeAggregation);

        $search->addAggregation($nestedAggregation);

        if ($request->query->has('types')) {
            $types = explode('|', $request->query->get('types'));

            foreach ($types as $type) {
                $search->addQuery(new NestedQuery('types', new TermQuery('types.name', $type)));
            }
        }

        if ($request->query->has('producers')) {
            $producers = explode('|', $request->query->get('producers'));

            foreach ($producers as $producer) {
                $search->addQuery(new TermQuery('producerName.raw', $producer));
            }
        }

        if ($request->query->has('term')) {
            $term = mb_strtolower($request->query->get('term'));
            $boolQuery = new BoolQuery();
            $boolQuery->addParameter('minimum_should_match', 1);
            $boolQuery->add(new FuzzyQuery('name', $term), BoolQuery::SHOULD);
            $boolQuery->add(new MatchPhraseQuery('name', $term), BoolQuery::SHOULD);
            $boolQuery->add(new WildcardQuery('name', '*' . $term . '*'), BoolQuery::SHOULD);
            $search->addQuery($boolQuery);
        }

        return $search;
    }
}
