<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\AttributeCollection;
use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Interfaces\AttributeInterface;

trait Attributes
{
    /**
     * @var array<string, AttributeCollection>
     */
    private array $attributes = [];


    public function getAttributeCollection(string $namespace = 'general'): AttributeCollection
    {
        if (!array_key_exists($namespace, $this->attributes)) {
            $this->attributes[$namespace] = new AttributeCollection();
        }
        return $this->attributes[$namespace];
    }

    public function getAttribute(string $name, string $namespace = 'general'): AttributeInterface|null
    {
        return $this->getAttributeCollection($namespace)->get($name);
    }

    /**
     * @param AttributeInterface[] $attributes
     */
    public function setAttributesWithClear(array $attributes, string $namespace = 'general'): self
    {
        $this->getAttributeCollection($namespace)->clear();
        return $this->setAttributes($attributes, $namespace);
    }

    /**
     * @param AttributeInterface[] $attributes
     */
    public function setAttributes(array $attributes, string $namespace = 'general'): self
    {
        foreach ($attributes as $attribute) {
            $this->setAttribute($attribute, $namespace);
        }

        return $this;
    }


    public function setAttribute(AttributeInterface $attribute, string $namespace = 'general'): self
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->remove($attribute);
        $this->addAttribute($attribute, $namespace);
        return $this;
    }

    /**
     * @param AttributeInterface[] $attributes
     */
    public function addAttributes(array $attributes, string $namespace = 'general'): self
    {
        foreach ($attributes as $attribute) {
            $this->addAttribute($attribute, $namespace);
        }

        return $this;
    }

    public function addAttribute(AttributeInterface $attribute, string $namespace = 'general'): self
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->add($attribute);
        return $this;
    }

    /**
     * @see getAttributeCollection
     */
    public function getAttributes(string $namespace = 'general'): AttributeCollection
    {
        return $this->getAttributeCollection($namespace);
    }

    public function getAttributesString(string $namespace = 'general'): string
    {
        $attributesStringed = (string)$this->getAttributeCollection($namespace);

        if (empty($attributesStringed)) {
            return '';
        }

        return ' ' . $attributesStringed;
    }

    public function removeAttribute(string|AttributeInterface $attribute, string $namespace = 'general'): self
    {
        $this->getAttributeCollection($namespace)->remove($attribute);
        return $this;
    }

    public function addClass(string $class, string $namespace = 'general'): self
    {
        $attrCollection = $this->getAttributeCollection($namespace);
        $attr = $attrCollection->get('class');
        if ($attr === null) {
            $attr = AttributeFactory::create('class');
            $attrCollection->add($attr);
        }
        $attr->add($class);
        return $this;
    }

    /**
     * @param string[] $classes
     */
    public function addClasses(array $classes, string $namespace = 'general'): self
    {
        foreach ($classes as $class) {
            $this->addClass($class, $namespace);
        }
        return $this;
    }

    public function getClassesList(string $namespace = 'general'): array
    {
        return $this->getAttribute('class', $namespace)?->getValues() ?? [];
    }

    public function removeClass(string $classValue, string $namespace = 'general'): self
    {
        $this->getAttribute('class', $namespace)?->remove($classValue);
        return $this;
    }
}
