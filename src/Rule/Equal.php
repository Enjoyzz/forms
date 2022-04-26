<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;

/**
 * @example
 * new Equal($message, ['expect']); or
 * new Equal($message, (array) 'expect'); or
 * new Equal($message, ['expect', 1, '255']);
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
     * @psalm-suppress PossiblyNullReference
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool
    {

        $method = $this->getRequest()->getRequest()->getMethod();
        $requestData = match (strtolower($method)) {
            'get' => $this->getRequest()->getQueryData()->getAll(),
            'post' => $this->getRequest()->getPostData()->getAll(),
            default => []
        };
        $value = \getValueByIndexPath($element->getName(), $requestData);

        if (false === $this->check($value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }


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

        return array_search($value, $this->getParams());
    }
}
