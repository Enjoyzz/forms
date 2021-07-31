<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Traits;

class Text extends Element
{
    use Traits\Description;
    use Traits\Rules;

    protected string $type = 'text';
}
