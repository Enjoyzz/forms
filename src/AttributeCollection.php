<?php

declare(strict_types=1);


namespace Enjoys\Forms;


final class AttributeCollection implements \Countable, \IteratorAggregate
{

    /**
     * @var Attribute[]
     */
    private array $collection = [];

    public function __construct()
    {
    }

    public function count()
    {
        return count($this->collection);
    }

    public function has(Attribute $attribute): bool
    {
        foreach ($this->collection as $item) {
            if ($item->getName() === $attribute->getName()) {
                return true;
            }
        }
        return false;
    }

    public function add(Attribute $attribute): AttributeCollection
    {
        if (!$this->has($attribute)) {
            $this->collection[] = $attribute;
        }
        return $this;
    }

    public function get(string $name): Attribute|null
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

    public function remove(string|Attribute $element): AttributeCollection
    {
        $attributeName = ($element instanceof Attribute) ? $element->getName() : $element;

        foreach ($this->collection as $key => $item) {
            if ($item->getName() === $attributeName) {
                unset($this->collection[$key]);
                break;
            }
        }

        return $this;
    }

    public function replace(Attribute $attribute): AttributeCollection
    {
        $this->remove($attribute->getName());

        return $this->add($attribute);
    }

    public function __toString(): string
    {
        return implode(
            ' ',
            array_filter($this->collection, function ($item) {
                return !empty($item->__toString());
            })
        );
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->collection);
    }


//    public function offsetExists(mixed $offset): bool
//    {
//        return isset($this->collection[$offset]) || array_key_exists($offset, $this->collection);
//    }
//
//    public function offsetGet(mixed $offset)
//    {
//        return $this->collection[$offset] ?? null;
//    }
//
//    public function offsetSet(mixed $offset, mixed $value)
//    {
//        if ($offset === null) {
//            $this->collection[] = $value;
//            return;
//        }
//
//        $this->collection[$offset] = $value;
//    }
//
//    public function offsetUnset(mixed $offset)
//    {
//        if (! isset($this->collection[$offset]) && ! array_key_exists($offset, $this->collection)) {
//            return null;
//        }
//
//        $removed = $this->collection[$offset];
//        unset($this->collection[$offset]);
//
//        return $removed;
//    }

}