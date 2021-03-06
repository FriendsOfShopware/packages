<?php

namespace App\Controller;

use App\Command\PackageIndexerCommand;
use MeiliSearch\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Search extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(): Response
    {
        return $this->render('search.html.twig');
    }

    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function ajaxSearch(Request $request, Client $client): Response
    {
        $index = $client->getIndex(PackageIndexerCommand::INDEX_NAME);

        $term = mb_strtolower((string) $request->query->get('term', ''));
        $options = [
            'facetsDistribution' => [
                'types',
                'producerName',
            ],
        ];

        $selectedTypes = array_filter(explode('|', (string) $request->query->get('types', '')));
        $selectedProducers = array_filter(explode('|', (string) $request->query->get('producers', '')));

        foreach ($selectedTypes as $selectedType) {
            $options['facetFilters'][] = 'types:' . $selectedType;
        }

        foreach ($selectedProducers as $selectedProducer) {
            $options['facetFilters'][] = 'producerName:' . $selectedProducer;
        }

        $searchResult = $index->search($term, $options);

        return $this->render('ajax/search.html.twig', [
            'searchResult' => $searchResult,
            'selectedTypes' => $selectedTypes,
            'selectedProducers' => $selectedProducers,
        ]);
    }
}
