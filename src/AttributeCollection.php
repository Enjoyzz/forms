<?php

declare(strict_types=1);


namespace Enjoys\Forms;


final class AttributeCollection implements \Countable, \ArrayAccess
{

    private array $collection = [];

    public function __construct()
    {
    }

    public function count()
    {
        return count($this->collection);
    }

    public function has(Attribute $attribute)
    {

    }

    public function add(Attribute $attribute): void
    {
        $this->collection[] = $attribute;
    }

   public function __toString(): string
   {
       return implode(' ', $this->collection);
   }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->collection[$offset]) || array_key_exists($offset, $this->collection);
    }

    public function offsetGet(mixed $offset)
    {
        return $this->collection[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value)
    {
        if ($offset === null) {
            $this->collection[] = $value;
            return;
        }

        $this->collection[$offset] = $value;
    }

    public function offsetUnset(mixed $offset)
    {
        if (! isset($this->collection[$offset]) && ! array_key_exists($offset, $this->collection)) {
            return null;
        }

        $removed = $this->collection[$offset];
        unset($this->collection[$offset]);

        return $removed;
    }
}