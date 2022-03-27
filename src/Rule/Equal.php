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
 * Description of Equal
 *
 * $form->text($name, $title)->addRule('equal', $message, ['expect']); or
 * $form->text($name, $title)->addRule('equal', $message, (array) 'expect'); or
 * $form->text($name, $title)->addRule('equal', $message, ['expect', 1, '255']);
 *
 * @author Enjoys
 */
class Equal extends Rules implements RuleInterface
{

    public function setMessage(?string $message = null): ?string
    {
        if (is_null($message)) {
            $message = 'Допустимые значения (указаны через запятую): ' . \implode(', ', $this->getParams());
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

        if (false === $this->check($value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return array-key|bool
     */
    private function check($value)
    {

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
        /**
         * @todo не пойму для чего это
         */
        return array_search(\trim((string) $value), $this->getParams());
    }
}
