<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Interfaces\Ruled;
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

    public function validate(Ruled $element): bool
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
