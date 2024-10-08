<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class HttpException extends Exception implements HttpExceptionInterface, JsonSchemaAwareRecord
{
    /** @return array<mixed> */
    public function getHeaders(): array
    {
        return [];
    }
}
