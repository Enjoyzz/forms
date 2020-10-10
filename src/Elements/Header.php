<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
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

namespace Enjoys\Forms\Elements;

/**
 * Description of Header
 *
 * @author deadl
 * 
 * @mixin \Enjoys\Forms\Element
 */
class Header extends \Enjoys\Forms\Element {

    private $closeAfterCountElements = 0;

    public function __construct(\Enjoys\Forms\FormDefaults $formDefaults, string $name, string $title = null) {
        parent::__construct($formDefaults, $name, $title);
        $this->setTitle($title);
        $this->setName(\uniqid('header'));
        $this->removeAttribute('name');
        $this->removeAttribute('id');        
    }
    public function __constructs($title) {
        //parent::__construct($name, $title)
        $this->setTitle($title);
        $this->setName(\uniqid('header'));
        $this->removeAttribute('name');
        $this->removeAttribute('id');
    }

    public function closeAfter(int $countElements) {
        $this->closeAfterCountElements = $countElements;
    }

    public function getCloseAfterCountElements() {
        return $this->closeAfterCountElements;
    }

    public function addFieldsetAttribute(...$attributes) {
        $this->setGroupAttributes('FieldsetAttribute');
        $this->addAttributes(...$attributes);
        $this->resetGroupAttributes();

        return $this;
    }

    public function getFieldsetAttributes(): string {
        $this->setGroupAttributes('FieldsetAttribute');
        $attributes = $this->getAttributes();
        $this->resetGroupAttributes();
        // dump($attributes);
        return $attributes;
    }
    
    public function getFieldsetAttribute($key): string {
        $this->setGroupAttributes('FieldsetAttribute');
        $attribute = $this->getAttribute($key);
        $this->resetGroupAttributes();
        // dump($attributes);
        return $attribute;
    }    

}
