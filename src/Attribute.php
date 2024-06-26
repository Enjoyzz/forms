<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Closure;
use Enjoys\Forms\Interfaces\AttributeInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

abstract class Attribute implements AttributeInterface
{
    protected string $name = '';

    /**
     * @var string[]
     */
    private array $values = [];

    protected bool $withoutValue = true;
    protected bool $fillNameAsValue = false;
    protected bool $multiple = false;
    protected string $separator = '';

    public function withName(string $name): AttributeInterface
    {
        $new = clone $this;
        $new->name = $name;
        return $new;
    }


    public function setWithoutValue(bool $withoutValue): AttributeInterface
    {
        $this->withoutValue = $withoutValue;
        return $this;
    }

    public function setFillNameAsValue(bool $fillNameAsValue): AttributeInterface
    {
        $this->fillNameAsValue = $fillNameAsValue;
        return $this;
    }

    public function setMultiple(bool $multiple, string $separator = ' '): AttributeInterface
    {
        $this->multiple = $multiple;
        $this->separator = $separator;
        return $this;
    }


    public function setSeparator(string $separator): AttributeInterface
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
        if ($this->getName() === '') {
            return '';
        }
        if ($this->withoutValue && empty($this->values)) {
            if ($this->fillNameAsValue) {
                return sprintf('%1$s="%1$s"', $this->getName());
            }
            return $this->getName();
        }

        if (!$this->withoutValue && empty($this->values)) {
            return '';
        }

        return sprintf('%s="%s"', $this->getName(), $this->getValueString());
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function getValueString(): string
    {
        return implode($this->separator, $this->getValues());
    }

    /**
     * @param string[] $values
     * @return void
     */
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

    public function has(string $value): bool
    {
        return in_array($value, $this->values, true);
    }


    /**
     * @param Closure|scalar|null $value
     * @return AttributeInterface
     */
    public function add($value): AttributeInterface
    {
        if ($value instanceof Closure) {
            /** @var null|scalar $value */
            $value = $value();
        }

        $value = $this->normalize($value);

        if ($value === null) {
            return $this;
        }

        if (!$this->multiple) {
            $this->clear();
        }

        $value = (!empty($this->separator)) ? explode($this->separator, $value) : (array) $value;

        foreach ($value as $item) {
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
     * @throws InvalidArgumentException
     */
    private function normalize(float|bool|int|string|null $value): ?string
    {
        Assert::nullOrScalar($value);
        return ($value === null) ? null : \htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401);
    }
}
