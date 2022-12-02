<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Interfaces\AttributeInterface;

/**
 * @psalm-suppress MissingTemplateParam
 */
final class AttributeCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var AttributeInterface[]
     */
    private array $collection = [];

    public function __construct()
    {
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function has(AttributeInterface $attribute): bool
    {
        foreach ($this->collection as $item) {
            if ($item->getName() === $attribute->getName()) {
                return true;
            }
        }
        return false;
    }

    public function add(AttributeInterface $attribute): AttributeCollection
    {
        if (!$this->has($attribute)) {
            $this->collection[] = $attribute;
        }
        return $this;
    }

    public function get(string $name): AttributeInterface|null
    {
        foreach ($this->collection as $item) {
            if ($item->getName() === $name) {
                return $item;
            }
        }
        return null;
    }

    public function clear(): void
    {
        $this->collection = [];
    }

    public function remove(string|AttributeInterface $element): AttributeCollection
    {
        $attributeName = ($element instanceof AttributeInterface) ? $element->getName() : $element;

        foreach ($this->collection as $key => $item) {
            if ($item->getName() === $attributeName) {
                unset($this->collection[$key]);
                return $this;
            }
        }

        return $this;
    }

    public function replace(AttributeInterface $attribute): AttributeCollection
    {
        $this->remove($attribute->getName());

        return $this->add($attribute);
    }

    public function __toString(): string
    {
        return implode(' ', array_filter($this->collection, fn($item) => !empty($item->__toString())));
    }


    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->collection);
    }
}
