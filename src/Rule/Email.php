<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;

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
     * @psalm-suppress PossiblyNullReference
     * @param Ruleable&Element $element
     * @return bool
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
        if ($this->check(\trim($value)) === false) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }


    private function check(string $value): bool
    {
        if (empty($value)) {
            return true;
        }

//        if ($this->idn_to_ascii === true) {
//            $value = idn_to_ascii($value);
//        }

        return (bool)filter_var($value, \FILTER_VALIDATE_EMAIL);
    }
}
