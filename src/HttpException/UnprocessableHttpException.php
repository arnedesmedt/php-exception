<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use Symfony\Component\HttpFoundation\Response;

abstract class UnprocessableHttpException extends HttpException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}
