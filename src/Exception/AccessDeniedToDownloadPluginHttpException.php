<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessDeniedToDownloadPluginHttpException extends HttpException
{
    public function __construct(\Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(403, 'Plugin cannot be downloaded. Access denied from API', $previous, $headers, $code);
    }
}
