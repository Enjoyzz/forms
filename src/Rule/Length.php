<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;

class Length extends Rules implements RuleInterface
{
    /**
     * @var string[]
     */
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

        /** @var string|int|array|false $value */
        $value = \getValueByIndexPath($element->getName(), $requestData);

        if (!$this->check($value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }


    /**
     * @param string|int|array|false $value
     * @return bool
     * @throws ExceptionRule
     */
    private function check(string|int|array|false $value): bool
    {
        if (is_array($value) || $value === false) {
            return true;
        }

        $length = \mb_strlen(\trim((string)$value), 'UTF-8');
        if (empty($value)) {
            return true;
        }

        /** @var string $operator */
        /** @var int|string $threshold */
        foreach ($this->getParams() as $operator => $threshold) {
            $method = $this->operatorToMethodTranslation[$operator] ?? 'unknown';

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
     * @param int $value
     * @param int|string $threshold
     * @return bool
     */
    private function equal(int $value, int|string $threshold): bool
    {
        return $value == $threshold;
    }

    /**
     *
     * @param int $value
     * @param int|string $threshold
     * @return bool
     */
    private function notEqual(int $value, int|string $threshold): bool
    {
        return $value != $threshold;
    }

    /**
     *
     * @param int $value
     * @param int|string $threshold
     * @return bool
     */
    private function greaterThan(int $value, int|string $threshold): bool
    {
        return $value > $threshold;
    }

    /**
     *
     * @param int $value
     * @param int|string $threshold
     * @return bool
     */
    private function lessThan(int $value, int|string $threshold): bool
    {
        return $value < $threshold;
    }

    /**
     *
     * @param int $value
     * @param int|string $threshold
     * @return bool
     */
    private function greaterThanOrEqual(int $value, int|string $threshold): bool
    {
        return $value >= $threshold;
    }

    /**
     *
     * @param int $value
     * @param int|string $threshold
     * @return bool
     */
    private function lessThanOrEqual(int $value, int|string $threshold): bool
    {
        return $value <= $threshold;
    }
}
