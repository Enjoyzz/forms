<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;

class Email extends Element implements Ruleable, Descriptionable
{
    use Description;
    use Rules;

    protected string $type = 'email';

    public function setMultiple(): self
    {
        $this->setAttribute(AttributeFactory::create('multiple'));
        return $this;
    }
}
