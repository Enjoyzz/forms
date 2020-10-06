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

use \Enjoys\Forms\Traits\Fill,
    \Enjoys\Forms\Interfaces,
    \Enjoys\Forms\Element;

/**
 * Description of Select
 *
 * @author deadl
 */
class Select extends Element {

    use Fill;

    /**
     *
     * @var string 
     */
    protected string $type = 'option';
    protected array $defaults = [];

    public function __construct(string $name, string $title = null) {
        parent::__construct($name, $title);
        // $this->setIndexKeyFill('value');
    }

    public function addAttributes(...$attributes): self {
        parent::addAttributes(...$attributes);
        if ($this->getAttribute('multiple') !== false && \substr($this->getName(), -2) !== '[]') {
            $this->setName($this->getName() . '[]');
        }
        return $this;
    }

    public function setDefault() : self{
        $data = \Enjoys\Forms\Forms::getDefaults();
        if (isset($data[$this->getName()])) {
            $this->defaults  = (array) $data[$this->getName()];
        }
        return $this;

    }

}
