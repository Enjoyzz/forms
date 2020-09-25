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
 * Description of Option
 *
 * @author deadl
 */
class Option extends \Enjoys\Forms\Element {

    protected string $type = 'option';

    public function __construct(string $name, string $title = null) {
        parent::__construct($name, $title);
        $this->setValue($name);
        $this->setId($name);
        $this->removeAttribute('name');
    }

    public function setDefault(array $data) {

        if (isset($data['data'][$data['name']])) {
            $_defaults = $data['data'][$data['name']];
            if(!is_array($_defaults)){
                $_defaults = (array) $_defaults;
            }
            if (in_array($this->getAttribute('value'), $_defaults)) {

                $this->addAttribute('selected');
            }
        }
    }

}