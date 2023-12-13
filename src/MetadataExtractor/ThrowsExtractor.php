<?php

declare(strict_types=1);

namespace ADS\Exception\MetadataExtractor;

use ADS\Exception\Attribute\Throws;
use ADS\Util\MetadataExtractor\MetadataExtractor;
use ReflectionClass;
use ReflectionNamedType;

use function array_merge;

class ThrowsExtractor
{
    public function __construct(
        private readonly MetadataExtractor $metadataExtractor,
    ) {
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     *
     * @return class-string[]
     */
    public function exceptionsFromReflectionClass(ReflectionClass $reflectionClass): array
    {
        /** @var Throws|null $throws */
        $throws = $this->metadataExtractor->attributeOrClassFromReflectionClass($reflectionClass, [Throws::class]);

        $exceptions = $throws?->exceptions() ?? [];

        foreach ($reflectionClass->getProperties() as $property) {
            /** @var ReflectionNamedType|null $propertyType */
            $propertyType = $property->getType();

            if ($propertyType === null || $propertyType->isBuiltin()) {
                continue;
            }

            /** @var class-string $propertyClass */
            $propertyClass = $propertyType->getName();
            $propertyReflectionClass = new ReflectionClass($propertyClass);
            $propertyExceptions = $this->exceptionsFromReflectionClass($propertyReflectionClass);

            $exceptions = array_merge($exceptions, $propertyExceptions);
        }

        return $exceptions;
    }
}
