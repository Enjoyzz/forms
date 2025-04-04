<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Traits\Container;
use Enjoys\Forms\Traits\Description;
use Webmozart\Assert\Assert;

class Group extends Element implements Descriptionable
{
    use Description;
    use Container;

    public function __construct(?string $title = null, ?string $id = null)
    {
        parent::__construct($id ?? \uniqid('group'), $title);
    }

    /**
     * @param Element[] $elements
     * @return $this
     */
    public function add(array $elements = []): Group
    {
        foreach ($elements as $element) {
            /** @psalm-suppress RedundantConditionGivenDocblockType */
            Assert::isInstanceOf($element, Element::class);
            $element->setForm($this->getForm());
            $this->addElement($element);
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $return = sprintf("<div id='%s'>", $this->getName());
        foreach ($this->getElements() as $data) {
//            $data->setAttribute(AttributeFactory::create('placeholder', $data->getLabel()));
//            $data->setLabel(null);
            $return .= HtmlRenderer::createTypeRender($data)->render();
        }
        $return .= '</div>';
        return $return;
    }

    public function prepare(): bool
    {
        return false;
    }
}
