<?php

namespace App\Controller;

use App\Command\PackageIndexerCommand;
use MeiliSearch\Client;
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
        $index = $client->getIndex(PackageIndexerCommand::INDEX_NAME);

        $term = mb_strtolower($request->query->get('term'));
        $options = [
            'facetsDistribution' => [
                'types',
                'producerName'
            ]
        ];

        $selectedTypes = array_filter(explode('|', $request->query->get('types', '')));
        $selectedProducers = array_filter(explode('|', $request->query->get('producers', '')));

        if (!empty($selectedTypes)) {
            foreach ($selectedTypes as $selectedType) {
                $options['facetFilters'][] = 'types:' . $selectedType;
            }
        }

        if (!empty($selectedProducers)) {
            foreach ($selectedProducers as $selectedProducer) {
                $options['facetFilters'][] = 'producerName:' . $selectedProducer;
            }
        }

        $searchResult = $index->search($term, $options);

        return $this->render('ajax/search.html.twig', [
            'searchResult' => $searchResult,
            'selectedTypes' => $selectedTypes,
            'selectedProducers' => $selectedProducers,
        ]);
    }
}
