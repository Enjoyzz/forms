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

use Enjoys\Forms\Element;
use Enjoys\Forms\Forms;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Helpers\Arrays;

/**
 * Description of Option
 *
 * @author deadl
 */
class Option extends Element
{
    use Fill;

    protected string $type = 'option';

    public function __construct(\Enjoys\Forms\FormDefaults $formDefaults, string $name, string $title = null)
    {
        parent::__construct($formDefaults, $name, $title);
        $this->setValue($name);
        $this->setId($name);
        $this->removeAttribute('name');
    }

    public function setDefault(): self
    {

      //$value = Arrays::getValueByIndexPath($this->getParentName(), $this->formDefaults->getDefaults());
        $value = $this->formDefaults->getValue($this->getParentName());
     
        if (is_array($value)) {
            if (in_array($this->getAttribute('value'), $value)) {
                $this->addAttributes('selected');
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttribute('value') == $value) {
                $this->addAttributes('selected');
                return $this;
            }
        }
        return $this;
    }
}
