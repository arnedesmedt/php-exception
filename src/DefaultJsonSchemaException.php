<?php

declare(strict_types=1);

namespace ADS\Exception;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use ADS\Util\StringUtil;

use function sprintf;
use function strrchr;

trait DefaultJsonSchemaException
{
    use JsonSchemaAwareRecordLogic;

    protected string $type;
    protected string $detail;

    private function init(): void
    {
        $this->detail = $this->detail();
    }

    /**
     * @return array<string, string>
     *
     * @phpcsSuppress SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
     */
    private static function __defaultProperties(): array
    {
        return [
            'type' => sprintf(
                '/problem/%s',
                StringUtil::decamelize(strrchr(static::class, '\\'), '-'),
            ),
        ];
    }

    abstract protected function detail(): string;
}
