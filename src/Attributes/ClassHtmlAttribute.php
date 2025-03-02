<?php

declare(strict_types=1);

namespace Enjoys\Forms\Attributes;

use Enjoys\Forms\HtmlAttribute;

final class ClassHtmlAttribute extends HtmlAttribute
{
    protected string $name = 'class';

    public function __construct()
    {
        $this->setMultiple(true);
        $this->setWithoutValue(false);
    }
}
