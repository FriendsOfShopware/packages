<?php

namespace App\Components\Api\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TokenMissingException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'Token is missing');
    }
}
