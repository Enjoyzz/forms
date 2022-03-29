<?php

declare(strict_types=1);


namespace Enjoys\Forms;

use Webmozart\Assert\Assert;

final class Attribute
{
    private array $values = [];

    private bool $multiple = false;
    private string $separator = '';

    public function __construct(private string $name, mixed $value = null)
    {
        if ($name === 'class') {
            $this->setMultiple(true);
            $this->setSeparator(' ');
        }

        if ($value !== null) {
            $this->add($value);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
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

        if (!$this->isMultiple()) {
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


    public function isMultiple(): bool
    {
        return $this->multiple;
    }


    public function setMultiple(bool $multiple): void
    {
        $this->multiple = $multiple;
    }


    public function getSeparator(): string
    {
        return $this->separator;
    }


    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }
}