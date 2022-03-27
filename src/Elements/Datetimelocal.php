<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;


class Datetimelocal extends Element implements Ruled
{
    use Description;
    use Rules;

    protected string $type = 'datetime-local';
}
