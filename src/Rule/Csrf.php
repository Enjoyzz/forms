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

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\Rule;
use Enjoys\Forms\Rules;

/**
 * Description of Required
 *
 * Csrf element will automatically set the rule(s)
 * and the form itself determines when csrf is needed
 *
 * @author Enjoys
 */
class Csrf extends Rules implements Rule
{

    /**
     *
     * @param string|null $message
     * @return void
     */
    public function setMessage(?string $message): void
    {
        if (is_null($message)) {
            $message = 'CSRF Attack detected';
        }
        parent::setMessage($message);
    }

    /**
     *
     * @param Element $element
     * @return bool
     */
    public function validate(Element $element): bool
    {
        if (!$this->check($this->request->post(Form::_TOKEN_CSRF_))) {
            $element->setRuleError($this->getMessage());
            // throw new \Enjoys\Forms\Exception($this->getMessage());
            return false;
        }

        return true;
    }

    /**
     *
     * @param type $value
     * @return type
     */
    private function check($value)
    {
        return hash_equals($value, crypt($this->getParam('csrf_key'), $value));
    }
}
