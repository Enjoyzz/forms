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

namespace Enjoys\Forms2\Traits;

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

        $this->attributeHandler->setAttributes($attributes, $namespace);
        return $this;
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

        $this->attributeHandler->setAttribute($name, $value, $namespace);
        return $this;
    }

    public function getAttribute($key, $namespace = 'general')
    {
        return $this->attributeHandler->getAttribute($key, $namespace);
    }

    /**
     *
     * @return string
     */
    public function getAttributes($namespace = 'general'): string
    {
        return $this->attributeHandler->getAttributes($namespace);
    }

    public function removeAttribute($key, $namespace = 'general'): self
    {
        $this->attributeHandler->removeAttribute($key, $namespace);
        return $this;
    }

    /**
     * Не протестирована, может вести себя не корректно
     * @param mixed $class
     * @return $this
     */
    public function addClass($class, $namespace = 'general')
    {
        $this->attributeHandler->addClass($class, $namespace);

        return $this;
    }

    public function removeClass($classValue, $namespace = 'general')
    {
        $this->attributeHandler->removeClass($classValue, $namespace);
        return $this;
    }
}
