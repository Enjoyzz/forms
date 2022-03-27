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

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Rules;

/**
 * Description of Email
 *
 * @author Enjoys
 */
class Email extends Rules implements RuleInterface
{

//    private $idn_to_ascii = false;

    public function setMessage(?string $message = null): ?string
    {
        if (is_null($message)) {
            $message = 'Не правильно введен email';
        }
        return parent::setMessage($message);
    }

    /**
     * @psalm-suppress UndefinedMethod
     * @param Element $element
     * @return bool
     */
    public function validate(Element $element): bool
    {

        $method = $this->getRequestWrapper()->getRequest()->getMethod();
        $requestData = match(strtolower($method)){
            'get' => $this->getRequestWrapper()->getQueryData()->getAll(),
            'post' => $this->getRequestWrapper()->getPostData()->getAll(),
            default => []
        };
        $value = \getValueByIndexPath($element->getName(), $requestData);
        if (!$this->check(\trim($value))) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @return bool|string
     */
    private function check(string $value)
    {
        if (empty($value)) {
            return true;
        }

//        if ($this->idn_to_ascii === true) {
//            $value = idn_to_ascii($value);
//        }

        return filter_var($value, \FILTER_VALIDATE_EMAIL);
    }
}
