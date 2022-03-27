<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;


class Html extends Element
{

    public function __construct(string $title)
    {
        parent::__construct(\uniqid('html'), $title);
        $this->setLabel($title);
        $this->removeAttribute('name');
        $this->removeAttribute('id');
    }

    public function baseHtml(): string
    {
        return $this->getLabel() ?? '';
    }
}
