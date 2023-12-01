<?php

declare(strict_types=1);

namespace ADS\Exception;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use ADS\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;

use function array_combine;
use function array_diff_key;
use function array_flip;
use function array_map;
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
        $propertiesByName = array_combine(
            array_map(
                static fn (ReflectionProperty $property): string => $property->getName(),
                $properties,
            ),
            $properties,
        );
        $propertiesByName = array_diff_key($propertiesByName, array_flip(self::PROPERTIES_TO_IGNORE));
        $propertyTypeMap = [];

        foreach ($propertiesByName as $propertyName => $property) {
            $type = self::typeFromProperty($property, $refObj);

            $propertyTypeMap[$propertyName] = [
                $type->getName(),
                self::isScalarType($type->getName()),
                $type->allowsNull(),
            ];
        }

        return $propertyTypeMap;
    }

    abstract protected function detail(): string;
}
