<?php

declare(strict_types=1);

namespace ADS\Exception;

use ADS\Util\StringUtil;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;
use ReflectionClass;

use function sprintf;

trait DefaultJsonSchemaException
{
    use JsonSchemaAwareRecordLogic;

    protected string $type;
    protected string $detail;

    private function init(): void
    {
        $this->type = sprintf(
            '/problem/%s',
            StringUtil::decamelize((new ReflectionClass($this))->getShortName(), '-'),
        );
        $this->detail = $this->detail();
    }

    abstract protected function detail(): string;
}
