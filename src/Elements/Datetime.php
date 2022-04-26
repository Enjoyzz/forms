<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;

class Datetime extends Element implements Ruled, Descriptionable
{
    use Description;
    use Rules;

    protected string $type = 'datetime';
}
