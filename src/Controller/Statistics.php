<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Statistics extends AbstractController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route(path="/statistics", name="statistics")
     */
    public function index(): Response
    {
        return $this->render('statistics.html.twig', [
            'totals' => $this->getTotals(),
            'downloads30Days' => $this->formatForChartJs($this->getDownloadsLast30Days()),
            'downloadsByMonths' => $this->formatForChartJs($this->getDownloadsByMonths()),
        ]);
    }

    private function getTotals(): array
    {
        $packagesCount = (int) $this->connection->fetchColumn('SELECT COUNT(*) FROM package');
        $versionCount = (int) $this->connection->fetchColumn('SELECT COUNT(*) FROM version');
        $downloadCount = (int) $this->connection->fetchColumn('SELECT COUNT(*) FROM download');

        return [
            'packageCount' => $packagesCount,
            'versionCount' => $versionCount,
            'downloadCount' => $downloadCount,
        ];
    }

    private function getDownloadsLast30Days(): array
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
        $current = new \DateTimeImmutable();
        $past = $current->sub(new \DateInterval('P30D'));

        return $this->connection->executeQuery($sql, [
            'from' => $past->format('Y-m-d'),
            'to' => $current->format('Y-m-d'),
        ])->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    private function getDownloadsByMonths(): array
    {
        $sql = <<<SQL
SELECT
EXTRACT( YEAR_MONTH FROM download.installed_at ) as month,
COUNT(*) as downloads
FROM download
GROUP BY EXTRACT( YEAR_MONTH FROM download.installed_at )
SQL;

        return $this->connection->executeQuery($sql)->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    private function formatForChartJs(array $data): array
    {
        return [
            'keys' => json_encode(array_keys($data)),
            'values' => json_encode(array_map('intval', array_values($data))),
            'length' => count($data),
        ];
    }
}
