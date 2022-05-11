<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\ServerRequestWrapperInterface;

interface RuleInterface
{
    /**
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool;

    public function setRequest(ServerRequestWrapperInterface $request = null): void;


}
