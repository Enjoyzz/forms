<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rules;


class Submit extends Rules implements RuleInterface
{

    public function validate(Ruled $element): bool
    {

        $method = $this->getRequestWrapper()->getRequest()->getMethod();

        $requestData = match(strtolower($method)){
            'get' => $this->getRequestWrapper()->getQueryData()->getAll(),
            'post' => $this->getRequestWrapper()->getPostData()->getAll(),
            default => []
        };

        $value = \getValueByIndexPath($element->getName(), $requestData);

        return $this->check($value);
    }


    private function check($value): bool
    {
        return $value == $this->getParams()[0];
    }
}
