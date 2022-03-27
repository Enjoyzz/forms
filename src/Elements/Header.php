<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;


class Header extends Element
{

    private int $closeAfterCountElements = 0;

    public function __construct(string $title)
    {
        parent::__construct(\uniqid('header'), $title);
        $this->setLabel($title);
        $this->removeAttribute('name');
        $this->removeAttribute('id');
    }

    public function closeAfter(int $countElements): void
    {
        $this->closeAfterCountElements = $countElements;
    }

    public function getCloseAfterCountElements(): int
    {
        return $this->closeAfterCountElements;
    }


    public function baseHtml(): string
    {
        return $this->getLabel() ?? '';
    }
}
