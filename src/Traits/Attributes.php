<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

/**
 *
 * @author Enjoys
 */
trait Attributes
{

    /**
     *
     * @var array
     */
    private array $attributes = [];


    /**
     *
     * @param mixed $attributes
     * @return \self
     */
    public function setAttributes(array $attributes, string $namespace = 'general'): self
    {

        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $_value) {
                    $this->setAttribute($key, $value, $namespace);
                }
            }
            if (is_int($key)) {
                $key = $value;
                $value = null;
            }
            $this->setAttribute($key, $value, $namespace);
        }
        return $this;
        //dump($attributes);
//        if (is_array($attributes[0])) {
//            foreach ($attributes[0] as $name => $value) {
//                if (is_int($name)) {
//                    $name = $value;
//                    $value = null;
//                }
//                $this->setAttribute($name, $value);
//            }
//            return $this;
//        }
//
//        $this->setAttribute($attributes[0], (isset($attributes[1])) ? $attributes[1] : null);
//        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @param string $namespace
     * @return \self
     */
    public function setAttribute(string $name, string $value = null, string $namespace = 'general'): self
    {

        $name = \trim($name);

        if (in_array($name, ['class'])) {
            if (in_array($value, (array) $this->attributes[$namespace][$name])) {
                return $this;
            }
            $this->attributes[$namespace][$name][] = $value;
            return $this;
        }

        if (in_array($name, ['name'])) {
            if (
                    isset($this->attributes[$namespace][$name]) && $this->attributes[$namespace][$name] != $value
            ) {
                $this->attributes[$namespace][$name] = $value;
                $this->setName($value);
            }
        }

        $this->attributes[$namespace][$name] = $value;
        return $this;
    }

    public function getAttribute($key, $namespace = 'general')
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
     * @return string
     */
    public function getAttributes($namespace = 'general'): string
    {
        $str = [];
        if (!isset($this->attributes[$namespace])) {
            $this->attributes[$namespace] = [];
        }
        foreach ($this->attributes[$namespace] as $key => $value) {
            if (is_array($value)) {
                if (empty($value)) {
                    continue;
                }
                $str[] = " {$key}=\"" . \implode(" ", $value) . "\"";
                continue;
            }

            if (is_null($value)) {
                $str[] = " {$key}";
                continue;
            }


            $str[] = " {$key}=\"{$value}\"";
        }
        return implode("", $str);
    }

    public function removeAttribute($key, $namespace = 'general'): self
    {
        if (isset($this->attributes[$namespace][$key])) {
            unset($this->attributes[$namespace][$key]);
        }
        return $this;
    }

    /**
     * Не протестирована, может вести себя не корректно
     * @param mixed $class
     * @return $this
     */
    public function addClass($class, $namespace = 'general')
    {
        $values = explode(" ", (string) $class);
        foreach ($values as $value) {
            $this->setAttribute('class', (string) $value, $namespace);
        }
        
        return $this;
    }

    public function removeClass($classValue, $namespace = 'general')
    {
        if (!isset($this->attributes[$namespace]['class'])) {
            return $this;
        }

        if (false !== $key = array_search($classValue, (array) $this->attributes[$namespace]['class'])) {
            unset($this->attributes[$namespace]['class'][$key]);
        }
        return $this;
    }
}
