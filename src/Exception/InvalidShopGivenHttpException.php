<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidShopGivenHttpException extends HttpException
{
    public function __construct(string $domain, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(403, sprintf('Cannot find domain "%s" in your Account', $domain), $previous, $headers, $code);
    }
}
