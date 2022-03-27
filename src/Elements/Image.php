<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;

class Image extends Element
{

    protected string $type = 'image';

    public function __construct(string $name, string $src = null)
    {
        parent::__construct($name);
        if (!is_null($src)) {
            $this->setAttribute('src', $src);
        }
    }
}
