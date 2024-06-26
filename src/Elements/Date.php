<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;

class Date extends Element implements Ruleable, Descriptionable
{
    use Description;
    use Rules;

    protected string $type = 'date';

    public function setMin(\DateTimeInterface $date): self
    {
        $this->setAttribute(AttributeFactory::create('min', $date->format('Y-m-d')));
        return $this;
    }

    public function setMax(\DateTimeInterface $date): self
    {
        $this->setAttribute(AttributeFactory::create('max', $date->format('Y-m-d')));
        return $this;
    }
}
