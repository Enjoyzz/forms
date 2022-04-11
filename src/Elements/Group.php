<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Enjoys\Forms\Traits\Container;
use Enjoys\Forms\Traits\Description;


class Group extends Element
{
    use Description;
    use Container;

    public function __construct(string $title = null, string $id = null)
    {
        parent::__construct($id ?? \uniqid('group'), $title);
    }

    public function add(array $elements = []): Group
    {
        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $element->setForm($this->getForm());
                $this->addElement($element);
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $return = sprintf("<div id='%s'>", $this->getName());
        foreach ($this->getElements() as $data) {
//            $data->setAttr(AttributeFactory::create('placeholder', $data->getLabel()));
//            $data->setLabel(null);
            $return .= TypeRenderFactory::create($data)->render();
        }
        $return .= '</div>';
        return $return;
    }

    public function prepare()
    {
    }


}
