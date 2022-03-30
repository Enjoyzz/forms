<?php

declare(strict_types=1);


namespace Enjoys\Forms;

use Webmozart\Assert\Assert;

final class Attribute
{
    private array $values = [];

    private bool $withoutValue = true;

    private bool $fillNameAsValue = false;

    private bool $multiple = false;

    private string $separator = '';

    public function __construct(private string $name, mixed $value = null)
    {
        if ($name === 'class') {
            $this->setMultiple(true);
            $this->setWithoutValue(false);
            $this->setSeparator(' ');
        }

        if ($value !== null) {
            $this->add($value);
        }
    }

    public function setWithoutValue(bool $withoutValue): void
    {
        $this->withoutValue = $withoutValue;
    }

    public function setFillNameAsValue(bool $fillNameAsValue): void
    {
        $this->fillNameAsValue = $fillNameAsValue;
    }

    public function setMultiple(bool $multiple): void
    {
        $this->multiple = $multiple;
    }


    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
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



    public function add(mixed $value): void
    {
        $value = $this->normalize($value);

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

    private function normalize(mixed $value): string
    {
        if ($value instanceof \Closure) {
            $value = $value();
        }

        Assert::scalar($value);

        return (string)$value;
    }



}