<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;

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
     * @psalm-suppress PossiblyNullReference
     * @param Ruleable&Element $element
     * @return bool
     * @throws ExceptionRule
     */
    public function validate(Ruleable $element): bool
    {

        $method = $this->getRequest()->getRequest()->getMethod();
        $requestData = match (strtolower($method)) {
            'get' => $this->getRequest()->getQueryData()->toArray(),
            'post' => $this->getRequest()->getPostData()->toArray(),
            default => []
        };
        $value = \getValueByIndexPath($element->getName(), $requestData);

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
