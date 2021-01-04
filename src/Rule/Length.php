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
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rules;

/**
 * Description of Length
 *
 * @author Enjoys
 */
class Length extends Rules implements RuleInterface
{

    private array $operatorToMethodTranslation = [
        '==' => 'equal',
        '!=' => 'notEqual',
        '>' => 'greaterThan',
        '<' => 'lessThan',
        '>=' => 'greaterThanOrEqual',
        '<=' => 'lessThanOrEqual',
    ];

    public function setMessage(?string $message = null): ?string
    {
        if (is_null($message)) {
            $message = 'Ошибка ввода';
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

        //  var_dump($element->getName());

        $value = \getValueByIndexPath($element->getName(), $this->getRequest()->$method());

        // $input_value = $request->post($element->getName(), $request->get($element->getName(), ''));
        if (!$this->check($value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }

    /**
     *
     * @param mixed $value
     * @return bool
     * @throws ExceptionRule
     */
    private function check($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        $length = \mb_strlen(\trim((string) $value), 'UTF-8');
        if (empty($value)) {
            return true;
        }

        foreach ($this->getParams() as $operator => $threshold) {
            $method = 'unknown';

            if (isset($this->operatorToMethodTranslation[$operator])) {
                $method = $this->operatorToMethodTranslation[$operator];
            }

            if (!method_exists(Length::class, $method)) {
                throw new ExceptionRule('Unknown Compare Operator.');
            }

            if (!$this->$method($length, $threshold)) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $threshold
     * @return bool
     */
    private function equal($value, $threshold): bool
    {
        return $value == $threshold;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $threshold
     * @return bool
     */
    private function notEqual($value, $threshold): bool
    {
        return $value != $threshold;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $threshold
     * @return bool
     */
    private function greaterThan($value, $threshold): bool
    {
        return $value > $threshold;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $threshold
     * @return bool
     */
    private function lessThan($value, $threshold): bool
    {
        return $value < $threshold;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $threshold
     * @return bool
     */
    private function greaterThanOrEqual($value, $threshold): bool
    {
        return $value >= $threshold;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $threshold
     * @return bool
     */
    private function lessThanOrEqual($value, $threshold): bool
    {
        return $value <= $threshold;
    }
}
