<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Request;

class Email implements RuleInterface
{
    use Request;

//    private $idn_to_ascii = false;

    private string $message;

    public function __construct(?string $message = null)
    {
        $this->message = $message ?? 'Не правильно введен email';
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
        /** @var string $value */
        $value = \getValueByIndexPath($element->getName(), $requestData);
        if ($this->check(\trim($value)) === false) {
            $element->setRuleError($this->message);
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
