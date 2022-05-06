<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Closure;
use Enjoys\Forms\Attributes\Base;
use Enjoys\Forms\Interfaces\AttributeInterface;
use Webmozart\Assert\Assert;

final class AttributeFactory
{
    public static function create(string $name, mixed $value = null): AttributeInterface
    {
        return self::getClass($name)->add($value);
    }

    /**
     * @param array $attributesKeyValue
     * @return AttributeInterface[]
     */
    public static function createFromArray(array $attributesKeyValue): array
    {
        $attributes = [];

        /** @var Closure|scalar|null $value */
        foreach ($attributesKeyValue as $name => $value) {
            if (is_int($name) && is_string($value)) {
                $attributes[] = self::create($value);
                continue;
            }
            Assert::string($name);
            $attributes[] = self::create($name, $value);
        }
        return $attributes;
    }

    /**
     * @param string $name
     * @return AttributeInterface
     */
    private static function getClass(string $name): AttributeInterface
    {
        $normalizeName = ucfirst(strtolower($name));

        /** @var class-string<AttributeInterface> $classString */
        $classString = sprintf('\Enjoys\Forms\Attributes\%sAttribute', $normalizeName);
        $classFile = sprintf(__DIR__ . '/Attributes/%sAttribute.php', $normalizeName);

        if (is_file($classFile) && class_exists($classString)) {
            return new $classString();
        }

        return (new Base())->withName($name);
    }
}
