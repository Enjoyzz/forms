<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

/**
 * @author Enjoys
 */
trait Attributes
{

    private array $attributes = [];


    public function setAttributes(array $attributes, string $namespace = 'general'): self
    {
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $this->setAttribute($key, implode(" ", $value), $namespace);
                continue;
            }
            if (is_int($key)) {
                $key = $value;
                $value = null;
            }
            $this->setAttribute((string)$key, $value, $namespace);
        }
        return $this;
    }


    public function setAttribute(string $name, $value = null, string $namespace = 'general'): self
    {
        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);
        }

        $name = \trim($name);

        if ($name == 'class') {
            if (
                isset($this->attributes[$namespace][$name]) &&
                in_array($value, (array)$this->attributes[$namespace][$name])
            ) {
                return $this;
            }
            $this->attributes[$namespace][$name][] = $value;
            return $this;
        }

        if ($name == 'name') {
            if (
                !is_null($value) &&
                $value !== false &&
                isset($this->attributes[$namespace][$name]) &&
                $this->attributes[$namespace][$name] != $value
            ) {
                $this->attributes[$namespace][$name] = $value;
                $this->setName($value);
            }
        }

        $this->attributes[$namespace][$name] = $value;
        return $this;
    }

    public function getAttribute(string $key, string $namespace = 'general'): string|null|false|array
    {
        if (!isset($this->attributes[$namespace])) {
            $this->attributes[$namespace] = [];
        }
        if (array_key_exists($key, $this->attributes[$namespace])) {
            return $this->attributes[$namespace][$key];
        }
        return false;
    }

    /**
     *
     * @param string $namespace
     * @return array
     */
    public function getAttributes(string $namespace = 'general'): array
    {
        if (!isset($this->attributes[$namespace])) {
            $this->attributes[$namespace] = [];
        }

        return $this->attributes[$namespace];
    }

    /**
     *
     * @param string $namespace
     * @return string
     */
    public function getAttributesString(string $namespace = 'general'): string
    {
        $str = [];
        if (!array_key_exists($namespace, $this->attributes)) {
            $this->attributes[$namespace] = [];
        }
        foreach ($this->attributes[$namespace] as $key => $value) {
            if (is_array($value)) {
                if (empty($value)) {
                    continue;
                }
                $str[] = " $key=\"" . \implode(" ", $value) . "\"";
                continue;
            }

            if (is_null($value)) {
                $str[] = " $key";
                continue;
            }


            $str[] = " $key=\"$value\"";
        }
        return implode("", $str);
    }

    public function removeAttribute(string $key, string $namespace = 'general'): self
    {
        if (array_key_exists($key, $this->attributes[$namespace])) {
            unset($this->attributes[$namespace][$key]);
        }
        return $this;
    }

    public function addClass($class, string $namespace = 'general'): self
    {
        if (is_array($class)) {
            foreach ($class as $_class) {
                $this->addClass($_class, $namespace);
            }
            return $this;
        }
        $values = explode(" ", (string)$class);
        foreach ($values as $value) {
            $this->setAttribute('class', $value, $namespace);
        }

        return $this;
    }


    public function removeClass(string $classValue, string $namespace = 'general'): self
    {
        if (!array_key_exists('class', $this->attributes[$namespace])) {
            return $this;
        }

        if (false !== $key = array_search($classValue, (array)$this->attributes[$namespace]['class'])) {
            unset($this->attributes[$namespace]['class'][$key]);
        }
        return $this;
    }
}
