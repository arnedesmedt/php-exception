<?php

declare(strict_types=1);

namespace ADS\Exception;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use ADS\Util\StringUtil;
use ReflectionClass;

use function array_diff_key;
use function array_flip;
use function sprintf;
use function strrchr;

trait DefaultJsonSchemaException
{
    use JsonSchemaAwareRecordLogic;

    private const PROPERTIES_TO_IGNORE = [
        'message',
        'code',
        'file',
        'line',
        '__useMaxValues',
        '__propTypeMap',
        '__schema',
        '__arrayPropItemTypeMap',
    ];

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

    /** @return array<string, array{0: string, 1: bool, 2: bool}> */
    private static function buildPropTypeMap(): array
    {
        $refObj = new ReflectionClass(static::class);
        $properties = $refObj->getProperties();
        $propertyTypeMap = [];

        $properties = array_diff_key($properties, array_flip(self::PROPERTIES_TO_IGNORE));

        foreach ($properties as $prop) {
            $type = self::typeFromProperty($prop, $refObj);

            $propertyTypeMap[$prop->getName()] = [
                $type->getName(),
                self::isScalarType($type->getName()),
                $type->allowsNull(),
            ];
        }

        return $propertyTypeMap;
    }

    abstract protected function detail(): string;
}
