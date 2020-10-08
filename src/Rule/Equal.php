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

namespace Enjoys\Forms\Rule;

use Enjoys\Base\Request;
use Enjoys\Forms\Element;
use Enjoys\Forms\Forms;
use Enjoys\Forms\Interfaces\Rule;
use Enjoys\Forms\RuleBase;
use Enjoys\Helpers\Arrays;

/**
 * Description of Equal
 *
 * $form->text($name, $title)->addRule('equal', $message, ['expect']); or
 * $form->text($name, $title)->addRule('equal', $message, (array) 'expect'); or
 * $form->text($name, $title)->addRule('equal', $message, ['expect', 1, '255']);
 * 
 * @author deadl
 */
class Equal extends RuleBase implements Rule
{

    public function setMessage(?string $message): void {
        if (is_null($message)) {
            $message = 'Допустимые значения (указаны через запятую): ' . \implode(', ', $this->getParams());
        }
        parent::setMessage($message);
    }

    public function validate(Element $element): bool {

        $request = new Request();

        $_method = 'get';
        if (isset(Forms::getDefaults()[Forms::_FLAG_FORMMETHOD_])) {
            $_method = Forms::getDefaults()[Forms::_FLAG_FORMMETHOD_];
        }
        
      //  var_dump($element->getName());

        $_value = Arrays::getValueByIndexPath($element->getName(), $request->$_method());

        if (false === $this->check($_value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }

    private function check($value) {
        
        if ($value === false) {
            return true;
        }
       if (is_array($value)) {
    
            foreach ($value as $_val) {
                if (false === $this->check($_val)) {
                    return false;
                }
            }
            return true;
       }
        return array_search(\trim($value), $this->getParams());
    }

}
