<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\AttributeCollection;

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

    public function getAttr(string $name, string $namespace = 'general'): Attribute|null
    {
        return $this->getAttributeCollection($namespace)->get($name);
    }

    /**
     * @param array $attributes
     * @param string $namespace
     * @return $this
     */
    public function setAttrsWithClear(array $attributes, string $namespace = 'general')
    {
        $this->getAttributeCollection($namespace)->clear();
        return $this->setAttrs($attributes, $namespace);
    }

    /**
     * @param Attribute[] $attributes
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
     * @param Attribute $attribute
     * @param string $namespace
     * @return $this
     */
    public function setAttr(Attribute $attribute, string $namespace = 'general')
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->remove($attribute);
        $this->addAttr($attribute, $namespace);
        return $this;
    }

    /**
     * @param array $attributes
     * @param string $namespace
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
     * @param Attribute $attribute
     * @param string $namespace
     * @return $this
     */
    public function addAttr(Attribute $attribute, string $namespace = 'general')
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->add($attribute);
        return $this;
    }

    /**
     * @see getAttributeCollection
     * @param string $namespace
     * @return AttributeCollection
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
     * @param string|Attribute $attribute
     * @param string $namespace
     * @return $this
     */
    public function removeAttr(string|Attribute $attribute, string $namespace = 'general')
    {
        $this->getAttributeCollection($namespace)->remove($attribute);
        return $this;
    }

    public function addClass(mixed $class, string $namespace = 'general'): self
    {
        $attrCollection = $this->getAttributeCollection($namespace);
        $attr = $attrCollection->get('class');
        if ($attr === null) {
            $attr = new Attribute('class');
            $attrCollection->add($attr);
        }
        $attr->add($class);
        return $this;
    }

    /**
     * @param array $classes
     * @param string $namespace
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
        return  $this->getAttr('class', $namespace)?->getValues() ?? [];
    }

    /**
     * @param string $classValue
     * @param string $namespace
     * @return $this
     */
    public function removeClass(string $classValue, string $namespace = 'general')
    {
        $this->getAttr('class', $namespace)?->remove($classValue);
        return $this;
    }
}
