<?php

declare(strict_types=1);

namespace App\Controller;

use DateInterval;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Psr\Cache\CacheItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class Statistics extends AbstractController
{
    public function __construct(private Connection $connection, private CacheInterface $cache)
    {
    }

    #[Route('/statistics', name: 'statistics')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        return $this->render('statistics.html.twig', [
            'totals' => $this->getTotals(),
            'downloads30Days' => $this->getDownloadsLast30Days($chartBuilder),
            'downloadsByMonths' => $this->getDownloadsByMonths($chartBuilder),
        ]);
    }

    /**
     * @return array{'packageCount': int, 'versionCount': int, 'downloadCount': int}
     */
    private function getTotals(): array
    {
        $packagesCount = (int) $this->connection->fetchOne('SELECT COUNT(*) FROM package');
        $versionCount = (int) $this->connection->fetchOne('SELECT COUNT(*) FROM version');
        $downloadCount = (int) $this->connection->fetchOne('SELECT COUNT(*) FROM download');

        return [
            'packageCount' => $packagesCount,
            'versionCount' => $versionCount,
            'downloadCount' => $downloadCount,
        ];
    }

    private function getDownloadsLast30Days(ChartBuilderInterface $chartBuilder): Chart
    {
        $sql = <<<SQL
SELECT
DATE(download.installed_at) as day,
COUNT(*) as downloads
FROM download
INNER JOIN package ON package.id = package_id
WHERE DATE(download.installed_at) BETWEEN :from AND :to
GROUP BY DATE(download.installed_at)
SQL;
        $current = new DateTimeImmutable();
        $past = $current->sub(new DateInterval('P30D'));

        $values = $this->cache->get('download-30-days', function (CacheItemInterface $item) use ($sql, $past, $current) {
            $item->expiresAfter(300);

            return $this->connection->fetchAllKeyValue($sql, [
                'from' => $past->format('Y-m-d'),
                'to' => $current->format('Y-m-d'),
            ]);
        });

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => \array_keys($values),
            'datasets' => [
                [
                    'label' => 'Downloads',
                    'data' => \array_values($values),
                    'backgroundColor' => [
                        'rgba(22,75,229, .5)'
                    ],
                    'borderColor' => [
                        'rgb(22,75,229)'
                    ]
                ]
            ]
        ]);

        return $chart;
    }

    private function getDownloadsByMonths(ChartBuilderInterface $chartBuilder): Chart
    {
        $sql = <<<SQL
SELECT
EXTRACT( YEAR_MONTH FROM download.installed_at ) as month,
COUNT(*) as downloads
FROM download
GROUP BY EXTRACT( YEAR_MONTH FROM download.installed_at )
SQL;

        $values = $this->cache->get('download-by-months', function (CacheItemInterface $item) use ($sql) {
            $item->expiresAfter(300);

            return $this->connection->fetchAllKeyValue($sql);
        });

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => \array_keys($values),
            'datasets' => [
                [
                    'label' => 'Downloads',
                    'data' => \array_values($values),
                    'backgroundColor' => [
                        'rgba(22,75,229, .5)'
                    ],
                    'borderColor' => [
                        'rgb(22,75,229)'
                    ]
                ]
            ]
        ]);

        return $chart;
    }
}
