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
 * Description of Checkbox
 *
 * @author deadl
 */
class Checkbox extends Element implements Interfaces\Radio_Checkbox {

    use Fill;

    /**
     *
     * @var string 
     */
    protected string $type = 'checkbox';
    private static $prefix_id = 'cb_';
    protected array $defaults = [];

    public function __construct(string $name, string $title = null) {
        $construct_name = $name;
        if (\substr($name, -2) !== '[]') {
            $construct_name = $name . '[]';
        }
        parent::__construct($construct_name, $title);
        $this->setValue($name);
        $this->setId($this->getPrefixId() . $name);
        $this->removeAttribute('name');
        $this->setValidateName($name);
    }

    public function setPrefixId($prefix) {
        static::$prefix_id = $prefix;
        $this->setId(static::$prefix_id . $this->getName());
        return $this;
    }

    public function getPrefixId() {
        return static::$prefix_id;
    }

    public function setDefault(array $data) : self{

        if (in_array($this->getAttribute('value'), $data)) {
            $this->addAttribute('checked');
            return $this;
        }

        $_default_name = \substr($this->getName(), 0, -2);
        if (isset($data[$_default_name])) {
            $this->defaults = (array) $data[$_default_name];
        }
        
        return $this;
    }
    
  

}
