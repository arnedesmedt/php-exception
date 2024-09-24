<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

use function debug_backtrace;

abstract class HttpException implements HttpExceptionInterface, JsonSchemaAwareRecord
{
    /** @var array<mixed> */
    private array $debugBacktrace;

    /** @return array<mixed> */
    public function getHeaders(): array
    {
        return [];
    }

    public function getMessage(): string
    {
        return 'test';
    }

    public function getCode(): int
    {
        return 0;
    }

    public function getFile(): string
    {
        return 'test';
    }

    public function getLine(): int
    {
        return 0;
    }

    /** @return array<mixed> */
    public function getTrace(): array
    {
        return $this->debugBacktrace();
    }

    public function getTraceAsString(): string
    {
        return 'test';
    }

    public function getPrevious(): Throwable|null
    {
        return null;
    }

    public function __toString(): string
    {
        return 'test';
    }

    /** @return array<mixed> */
    private function debugBacktrace(): array
    {
        if (! isset($this->debugBacktrace)) {
            $this->debugBacktrace = debug_backtrace();
        }

        return $this->debugBacktrace;
    }
}
