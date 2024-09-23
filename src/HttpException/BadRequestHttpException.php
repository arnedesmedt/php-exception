<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use Symfony\Component\HttpFoundation\Response;

abstract class BadRequestHttpException extends HttpException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
