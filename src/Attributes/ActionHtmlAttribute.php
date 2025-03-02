<?php

declare(strict_types=1);

namespace Enjoys\Forms\Attributes;

use Enjoys\Forms\HtmlAttribute;

final class ActionHtmlAttribute extends HtmlAttribute
{
    protected string $name = 'action';

    public function __construct()
    {
        $this->setWithoutValue(false);
    }
}
