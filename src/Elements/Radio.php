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

namespace Enjoys\Forms\Elements;

use \Enjoys\Forms\Traits\Fill,
    \Enjoys\Forms\Interfaces,
    \Enjoys\Forms\Element;

/**
 * Class Radio
 *
 * @author Enjoys
 * 
 */
class Radio extends Element implements Interfaces\Radio_Checkbox {

    use Fill;

    /**
     *
     * @var string 
     */
    protected string $type = 'radio';
    private static $prefix_id = 'rb_';
    protected array $defaults = [];

    public function __construct(string $name, string $title = null) {
        parent::__construct($name, $title);
        $this->setValue($name);
        $this->setId($this->getPrefixId() . $name);
        $this->removeAttribute('name');
    }

    public function setPrefixId($prefix) {
        static::$prefix_id = $prefix;
        $this->setId(static::$prefix_id . $this->getName());
        return $this;
    }

    public function getPrefixId() {
        return static::$prefix_id;
    }

    public function setDefault(array $data) {


        if (in_array($this->getAttribute('value'), $data)) {
            $this->addAttribute('checked');
            return;
        }


        if (isset($data[$this->getName()])) {
            $this->defaults = (array) $data[$this->getName()];
        }
    }

}
