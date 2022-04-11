<?php

declare(strict_types=1);


namespace Enjoys\Forms;


use Closure;
use Enjoys\Forms\Attributes\Action;
use Enjoys\Forms\Attributes\Base;
use Enjoys\Forms\Attributes\Class_;
use Enjoys\Forms\Attributes\Id;
use Enjoys\Forms\Interfaces\AttributeInterface;
use Webmozart\Assert\Assert;

final class AttributeFactory
{

    /**
     * @param string $className
     * @return Closure(string):AttributeInterface
     */
    private static function getMappedClass(string $className): Closure
    {
        $mappedClasses = [
            'class' => function (): AttributeInterface {
                return new Class_();
            },
            'id' => function (): AttributeInterface {
                return new Id();
            },
            'action' => function (): AttributeInterface {
                return new Action();
            },
        ];

        return $mappedClasses[strtolower($className)] ?? function (string $name): AttributeInterface {
                return (new Base())->withName($name);
            };
    }

    public static function create(string $name, mixed $value = null): AttributeInterface
    {
        $closure = self::getMappedClass($name);
        return $closure($name)->add($value);
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
}