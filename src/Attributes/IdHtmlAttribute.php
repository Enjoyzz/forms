<?php

declare(strict_types=1);

namespace Enjoys\Forms\Attributes;

use Enjoys\Forms\HtmlAttribute;

final class IdHtmlAttribute extends HtmlAttribute
{
    protected string $name = 'id';

    public function __construct()
    {
        $this->setWithoutValue(false);
    }
}
