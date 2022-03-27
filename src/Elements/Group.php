<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Traits\Container;
use Enjoys\Forms\Traits\Description;


class Group extends Element
{
    use Description;
    use Container;

    public function __construct(string $title = null)
    {
        parent::__construct(\uniqid('group'), $title);
    }

    public function add(array $elements = []): self
    {
        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $element->setForm($this->getForm());
                $this->addElement($element);
            }
        }
        return $this;
    }

    public function prepare()
    {
    }
}
