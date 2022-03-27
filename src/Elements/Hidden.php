<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Rules;


class Hidden extends Element implements Ruled
{
    use Rules;

    protected string $type = 'hidden';

    public function __construct(string $name, ?string $value = null)
    {

        parent::__construct($name, null);

        if (!is_null($value)) {
            $this->setAttribute('value', $value);
        }
        $this->removeAttribute('id');
    }
}
