<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rules;


class Submit extends Rules implements RuleInterface
{

    /**
     * @psalm-suppress PossiblyNullReference
     * @param Ruled&Element $element
     * @return bool
     */
    public function validate(Ruled $element): bool
    {

        $method = $this->getRequest()->getRequest()->getMethod();

        $requestData = match(strtolower($method)){
            'get' => $this->getRequest()->getQueryData()->getAll(),
            'post' => $this->getRequest()->getPostData()->getAll(),
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
