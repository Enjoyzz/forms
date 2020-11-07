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
use Enjoys\Forms\Rule\RuleInterface;
use Enjoys\Forms\Rules;

/**
 * Description of Required
 *
 * $form->text($name, $title)->addRule('required');
 * $form->text($name, $title)->addRule('required', $message);
 *
 * @author Enjoys
 */
class Required extends Rules implements RuleInterface
{

    public function setMessage(?string $message = null): ?string
    {
        if (is_null($message)) {
            $message = 'Обязательно для заполнения, или выбора';
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

        $method = $this->getRequest()->getMethod();
        $_value = \getValueByIndexPath($element->getName(), $this->getRequest()->$method());
        if (!$this->check($_value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 
     * @param mixed $value
     * @return bool
     */
    private function check($value): bool
    {
        if (is_array($value)) {
            return count($value) > 0;
        }
        return \trim((string) $value) != '';
    }
}
