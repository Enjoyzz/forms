<?php

declare(strict_types=1);


namespace Enjoys\Forms;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class Attribute
{
    private string $name;

    private array $values = [];

    private bool $withoutValue = true;

    private bool $fillNameAsValue = false;

    private bool $multiple = false;

    private string $separator = '';


    public function __construct(string $name, mixed $value = null)
    {
        $this->name = $name;
        if ($name === 'class') {
            $this->setMultiple(true);
            $this->setWithoutValue(false);
        }


       $this->add($value);

    }

    public function withName(string $name): Attribute
    {
        $new = clone $this;
        $new->name = $name;
        return $new;
    }

    public static function create(string $name, mixed $value = null): Attribute
    {
        return new self($name, $value);
    }

    /**
     * @param array $attributesKeyValue
     * @return Attribute[]
     */
    public static function createFromArray(array $attributesKeyValue): array
    {
        $attributes = [];
        foreach ($attributesKeyValue as $name => $value) {
            if (!is_string($name) && is_string($value)){
                $name = $value;
                $value = null;
            }
            $attributes[] = self::create($name, $value);
        }
        return $attributes;
    }

    public function setWithoutValue(bool $withoutValue): Attribute
    {
        $this->withoutValue = $withoutValue;
        return $this;
    }

    public function setFillNameAsValue(bool $fillNameAsValue): Attribute
    {
        $this->fillNameAsValue = $fillNameAsValue;
        return $this;
    }

    public function setMultiple(bool $multiple, string $separator = ' '): Attribute
    {
        $this->multiple = $multiple;
        $this->separator = $separator;
        return $this;
    }


    public function setSeparator(string $separator): Attribute
    {
        $this->separator = $separator;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        if ($this->withoutValue && empty($this->values)){
            if ($this->fillNameAsValue){
                return sprintf('%1$s="%1$s"', $this->getName());
            }
            return  $this->getName();
        }

        if (!$this->withoutValue && empty($this->values)){
            return  '';
        }

        return sprintf('%s="%s"', $this->getName(), $this->getValueString());
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getValueString(): string
    {
        return implode($this->separator, $this->getValues());
    }

    public function set(array $values): void
    {
        $this->clear();
        foreach ($values as $item) {
            $this->add($item);
        }
    }

    public function clear(): void
    {
        $this->values = [];
    }

    public function has(mixed $value): bool
    {
        return in_array($value, $this->values, true);
    }



    public function add(mixed $value): Attribute
    {
        $value = $this->normalize($value);

        if($value === null){
            return $this;
        }

        if (!$this->multiple) {
            $this->clear();
        } else {
            $value = explode($this->separator, $value);
        }
        foreach ((array)$value as $item) {
            if (!$this->has($item)) {
                $this->values[] = $item;
            }
        }

        return $this;
    }

    public function remove(string $value): bool
    {
        $key = array_search($value, $this->values, true);

        if ($key === false) {
            return false;
        }

        unset($this->values[$key]);

        return true;
    }

    /**
     * @param mixed $value
     * @return string|null
     * @throws InvalidArgumentException
     */
    private function normalize(mixed $value): ?string
    {
        if ($value instanceof \Closure) {
            $value = $value();
        }

        Assert::nullOrScalar($value);

        return ($value === null) ? null : (string)$value;
    }



}