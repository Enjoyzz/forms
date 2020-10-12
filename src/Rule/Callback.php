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

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Rule;
use Enjoys\Forms\Rules;

/**
 * Description of Callback
 *
 * @example Callback class
 * class Check {
 *      public function _check() {
 *          return false;
 *      }
 * }
 * $form ->text('foo')
 *       ->addRule('callback', null, [
 *              [
 *                  new Check(),
 *                  '_check'
 *              ]
 *      ]);
 * 
 * @example Call static method
 * 
 * class ClassTest {
 *      static public function isCheck() {
 *          return false;
 *      }
 * }
 * $form->text('foo')->addRule('callback', null, 'ClassTest::isCheck');
 * 
 * 
 * @example Callback function
 * 
 * function check() {
 *      return false;
 * }
 * $form->text('foo')->addRule('callback', null, 'check');
 *  
 * 
 * @example Callback closure function (anonimous)
 * 
 * $form->text('foo')->addRule('callback', null, function () {
 *      return false;
 * });
 * 
 * 
 * @example Callback anonimous class
 * 
 *  $form->text('foo')->addRule('callback', null, new class {
 *      public $execute = 'test';
 *      public function test() {
 *          return false;
 *      }
 *  });
 * 
 * 
 * @author deadl
 */
class Callback extends Rules implements Rule
{

    /**
     * 
     * @param string|null $message
     * @return void
     */
    public function setMessage(?string $message): void {
        if (is_null($message)) {
            $message = 'Ошибка';
        }
        parent::setMessage($message);
    }

    /**
     * 
     * @param Element $element
     * @return bool
     */
    public function validate(Element $element): bool {
        if ($this->check() === false) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 
     * @return callback
     */
    private function check() {
        $callback = $this->getParam(0);

        if ($callback instanceof \Closure) {
            return $callback();
        }

        if (is_object($callback) && (new \ReflectionClass($callback))->isAnonymous()) {
            $func = 'execute';
            if (isset($callback->execute)) {
                $func = $callback->execute;
            }
            return $callback->$func();
        }

        $params = $this->getParams();
        array_shift($params);
        return call_user_func($callback, ...$params);
    }

}
