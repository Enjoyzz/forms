<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Datalist;

class DatalistRender extends BaseElement
{


    public function render(): string
    {
        return
            $this->renderLabel($this->element) .
            $this->renderDatalist($this->element) .
            $this->renderDescription($this->element) .
            $this->renderValidation($this->element) .
            '';
    }

    protected function renderDatalist(Element $element): string
    {
        $return = sprintf(
            '<input%s><datalist id="%s">',
            $element->getAttributesString(),
            (string) $element->getAttribute('list')
        );

        /** @var Datalist $element */
        foreach ($element->getElements() as $data) {
            //$return .= "<option value=\"{$data->getLabel()}\">";
            $return .= $this->renderBody($data);
        }
        $return .= "</datalist>";
        return $return;
    }
}
