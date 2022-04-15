<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\AttributeCollection;
use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Interfaces\AttributeInterface;

trait Attributes
{
    private array $attributes = [];

    private array $attr = [];


    public function getAttributeCollection(string $namespace = 'general'): AttributeCollection
    {
        if (!array_key_exists($namespace, $this->attr)) {
            $this->attr[$namespace] = new AttributeCollection();
        }
        return $this->attr[$namespace];
    }

    public function getAttr(string $name, string $namespace = 'general'): AttributeInterface|null
    {
        return $this->getAttributeCollection($namespace)->get($name);
    }

    /**
     * @return $this
     */
    public function setAttrsWithClear(array $attributes, string $namespace = 'general')
    {
        $this->getAttributeCollection($namespace)->clear();
        return $this->setAttrs($attributes, $namespace);
    }

    /**
     * @param AttributeInterface[] $attributes
     * @param string $namespace
     * @return $this
     */
    public function setAttrs(array $attributes, string $namespace = 'general')
    {
        foreach ($attributes as $attribute) {
            $this->setAttr($attribute, $namespace);
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function setAttr(AttributeInterface $attribute, string $namespace = 'general')
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->remove($attribute);
        $this->addAttr($attribute, $namespace);
        return $this;
    }

    /**
     * @return $this
     */
    public function addAttrs(array $attributes, string $namespace = 'general')
    {
        foreach ($attributes as $attribute) {
            $this->addAttr($attribute, $namespace);
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function addAttr(AttributeInterface $attribute, string $namespace = 'general')
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->add($attribute);
        return $this;
    }

    /**
     * @return AttributeCollection
     * @see getAttributeCollection
     */
    public function getAttrs(string $namespace = 'general'): AttributeCollection
    {
        return $this->getAttributeCollection($namespace);
    }

    /**
     *
     * @param string $namespace
     * @return string
     */
    public function getAttributesString(string $namespace = 'general'): string
    {
        $attributesStringed = (string)$this->getAttributeCollection($namespace);

        if (empty($attributesStringed)) {
            return '';
        }

        return ' ' . $attributesStringed;
    }

    /**
     * @return $this
     */
    public function removeAttr(string|AttributeInterface $attribute, string $namespace = 'general')
    {
        $this->getAttributeCollection($namespace)->remove($attribute);
        return $this;
    }

    public function addClass(mixed $class, string $namespace = 'general'): self
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
     * @return $this
     */
    public function addClasses(array $classes, string $namespace = 'general')
    {
        foreach ($classes as $class) {
            $this->addClass($class, $namespace);
        }
        return $this;
    }

    public function getClassesList(string $namespace = 'general'): array
    {
        return $this->getAttr('class', $namespace)?->getValues() ?? [];
    }

    /**
     * @return $this
     */
    public function removeClass(string $classValue, string $namespace = 'general')
    {
        $this->getAttr('class', $namespace)?->remove($classValue);
        return $this;
    }
}
