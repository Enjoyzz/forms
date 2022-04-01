<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\AttributeCollection;

/**
 * @author Enjoys
 */
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
     * @param array $attributes
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

//
//    public function setAttributes(array $attributes, string $namespace = 'general'): self
//    {
//        foreach ($attributes as $key => $value) {
//            if (is_array($value)) {
//                $this->setAttribute($key, implode(" ", $value), $namespace);
//                continue;
//            }
//            if (is_int($key)) {
//                $this->setAttribute($value, null, $namespace);
//                continue;
//            }
//            $this->setAttribute($key, $value, $namespace);
//        }
//        return $this;
//    }

//
//    public function setAttribute(string $name, $value = null, string $namespace = 'general'): self
//    {
//        $name = \trim($name);
//
//        $this->getAttributeCollection($namespace)->add(new Attribute($name, $value));
//
//        if ($value instanceof \Closure) {
//            $value = $value();
//            Assert::nullOrString($value);
//        }
//
//        if ($value === null) {
//            $this->attributes[$namespace][$name] = null;
//            return $this;
//        }
//
//        if (!is_string($value) && is_scalar($value)) {
//            $value = (string)$value;
//        }
//
//
//        $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);
//
//        if ($name === 'class') {
//            $classList = $this->getClassesList($namespace);
//
//            if (!in_array($value, $classList)) {
//                $value = implode(" ", array_merge($classList, [$value]));
//            }
//        }
//
//        $this->attributes[$namespace][$name] = $value;
//
////        if ($name == 'name' && $this->attributes[$namespace]['name'] !== $value) {
////            $this->setName($value);
////        }
//
//
//        return $this;
//    }

//    public function getAttr(string $key, string $namespace = 'general'): string|null|false
//    {
//        if (!isset($this->attributes[$namespace])) {
//            $this->attributes[$namespace] = [];
//        }
//        if (array_key_exists($key, $this->attributes[$namespace])) {
//            return $this->attributes[$namespace][$key];
//        }
//        return false;
//    }

//    /**
//     *
//     * @param string $namespace
//     * @return array
//     */
//    public function getAttrs(string $namespace = 'general'): array
//    {
//        if (!isset($this->attributes[$namespace])) {
//            $this->attributes[$namespace] = [];
//        }
//
//        return $this->attributes[$namespace];
//    }

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
//        $str = [];
//        if (!array_key_exists($namespace, $this->attributes)) {
//            $this->attributes[$namespace] = [];
//        }
//        foreach ($this->attributes[$namespace] as $key => $value) {
//            if (is_array($value)) {
//                if (empty($value)) {
//                    continue;
//                }
//                $str[] = " $key=\"" . \implode(" ", $value) . "\"";
//                continue;
//            }
//
//            if (is_null($value)) {
//                $str[] = " $key";
//                continue;
//            }
//
//
//            $str[] = " $key=\"$value\"";
//        }
//        return implode("", $str);
    }

//    public function removeAttribute(string $key, string $namespace = 'general'): self
//    {
//        if (array_key_exists($key, $this->attributes[$namespace])) {
//            unset($this->attributes[$namespace][$key]);
//        }
//        return $this;
//    }

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
//
//    public function addClasses(array $classes, string $namespace = 'general'): self
//    {
//        foreach ($classes as $class) {
//            $this->addClass($class, $namespace);
//        }
//        return $this;
//    }
//
//
//    public function getClassesList(string $namespace = 'general'): array
//    {
//        $classesString = $this->getAttr('class', $namespace);
//        if ($classesString === false || $classesString === null) {
//            return [];
//        }
//        return explode(" ", $classesString);
//    }
//
//    public function removeClass(string $classValue, string $namespace = 'general'): self
//    {
//        if (!array_key_exists('class', $this->attributes[$namespace])) {
//            return $this;
//        }
//
//        $classesList = $this->getClassesList($namespace);
//
//        if (false !== $key = array_search($classValue, $classesList)) {
//            unset($classesList[$key]);
//        }
//
//        $this->attributes[$namespace]['class'] = implode(" ", $classesList);
//
//        return $this;
//    }
}
