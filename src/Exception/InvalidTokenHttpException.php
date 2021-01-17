<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTokenHttpException extends HttpException
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(\Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid access token', $previous, $headers, $code);
    }
}
