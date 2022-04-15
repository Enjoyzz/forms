<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Optgroup;

class Select extends Input
{
    public function render(): string
    {
        return sprintf(
            "%s\n%s\n%s\n%s\n%s\n%s",
            $this->labelRender(),
            "<select{$this->getElement()->getAttributesString()}>",
            $this->bodyRender($this->getElement()),
            "</select>",
            $this->descriptionRender(),
            $this->validationRender(),
        );
    }

    protected function bodyRender(Element $element): string
    {
        $return = "";
        /** @var \Enjoys\Forms\Elements\Select $element  */
        foreach ($element->getElements() as $data) {
            if ($data instanceof Optgroup) {
                $return .= "<optgroup{$data->getAttributesString()}>";
                $return .= $this->bodyRender($data);
                $return .= "</optgroup>";
                continue;
            }
            $return .= parent::bodyRender($data);
        }
        return $return;
    }
}
