<?php

declare(strict_types=1);

namespace App\Components\Api\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    private array $errorMessageMapper = [
        'WildcardLicensesException-1' => 'Access to wildcard license forbidden',
        'WildcardLicensesException-2' => 'Duplicate name',
        'WildcardLicensesException-4' => 'Deserialization error. Contact Shopware Support',
        'WildcardLicensesException-5' => 'Wildcard Instance not approved. Contact Shopware Support',
        'WildcardLicensesException-6' => 'Wildcard Instance not found',
        'WildcardLicensesException-7' => 'Plugin not found in Wildcard Instance',
        'WildcardLicensesException-10' => 'Requested Shopware version not found',
    ];

    private array $errorHttpMapper = [
        'WildcardLicensesException-6' => Response::HTTP_NOT_FOUND,
        'WildcardLicensesException-7' => Response::HTTP_NOT_FOUND,
    ];

    public function __construct(string $code)
    {
        parent::__construct(
            $this->errorHttpMapper[$code] ?? Response::HTTP_BAD_REQUEST,
            $this->errorMessageMapper[$code] ?? $code
        );
    }
}
