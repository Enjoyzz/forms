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

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionElement;
use Enjoys\Forms\FormDefaults;

/**
 * Class Group
 *
 * @author Enjoys
 */
class Group extends Element
{

    private $elements = [];

    public function __construct(FormDefaults $formDefaults, string $title = null, array $elements = [])
    {
        parent::__construct($formDefaults, \uniqid('group'), $title);
        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $this->addElement($element);
            }
        }
    }

    public function __call(string $name, array $arguments)
    {
        $class_name = '\Enjoys\\Forms\Elements\\' . ucfirst($name);
        if (!class_exists($class_name)) {
            throw new ExceptionElement("Element <b>{$class_name}</b> not found");
        }
        /** @var Element $element */
        $element = new $class_name($this->formDefaults, ...$arguments);
        
        $this->addElement($element);
        return $element;
    }

    public function addElement(Element $element): self
    {
        $element->initRequest($this->getRequest());
        $this->elements[$element->getName()] = $element;
        return $this;
    }

    public function getElements(): array
    {
        return $this->elements;
    }
}
