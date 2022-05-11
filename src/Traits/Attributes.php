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
     * @param string $namespace
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function setAttributesWithClear(array $attributes, string $namespace = 'general')
    {
        $this->getAttributeCollection($namespace)->clear();
        return $this->setAttributes($attributes, $namespace);
    }

    /**
     * @param AttributeInterface[] $attributes
     * @param string $namespace
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function setAttributes(array $attributes, string $namespace = 'general')
    {
        foreach ($attributes as $attribute) {
            $this->setAttribute($attribute, $namespace);
        }

        return $this;
    }


    /**
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function setAttribute(AttributeInterface $attribute, string $namespace = 'general')
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->remove($attribute);
        $this->addAttribute($attribute, $namespace);
        return $this;
    }

    /**
     * @param AttributeInterface[] $attributes
     * @param string $namespace
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function addAttributes(array $attributes, string $namespace = 'general')
    {
        foreach ($attributes as $attribute) {
            $this->addAttribute($attribute, $namespace);
        }

        return $this;
    }


    /**
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function addAttribute(AttributeInterface $attribute, string $namespace = 'general')
    {
        $attributeCollection = $this->getAttributeCollection($namespace);
        $attributeCollection->add($attribute);
        return $this;
    }

    /**
     * @param string $namespace
     * @return AttributeCollection
     * @see getAttributeCollection
     */
    public function getAttributes(string $namespace = 'general'): AttributeCollection
    {
        return $this->getAttributeCollection($namespace);
    }

    /**
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
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function removeAttribute(string|AttributeInterface $attribute, string $namespace = 'general')
    {
        $this->getAttributeCollection($namespace)->remove($attribute);
        return $this;
    }

    /**
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function addClass(string $class, string $namespace = 'general')
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
     * @param string $namespace
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
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
        return $this->getAttribute('class', $namespace)?->getValues() ?? [];
    }

    /**
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function removeClass(string $classValue, string $namespace = 'general')
    {
        $this->getAttribute('class', $namespace)?->remove($classValue);
        return $this;
    }
}
