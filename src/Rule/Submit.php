<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;

class Submit extends Rules implements RuleInterface
{
    public function __construct(string $token)
    {
        parent::__construct(null, $token);
    }

    private function getToken()
    {
        return $this->getParams()[0];
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

        return $this->check($value);
    }


    private function check($value): bool
    {
        return $value == $this->getToken();
    }
}
