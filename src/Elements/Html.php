<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;


class Html extends Element
{

    public function __construct(string $title)
    {
        parent::__construct(\uniqid('html'), $title);
        $this->getAttributeCollection()
            ->remove('name')
            ->remove('id')
        ;
    }

    public function baseHtml(): string
    {
        return $this->getLabel() ?? '';
    }
}
