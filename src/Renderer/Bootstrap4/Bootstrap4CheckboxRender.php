<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Bootstrap4;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\ElementsRender\CheckboxRender;

/**
 * Class Bootstrap4CheckboxRender
 * @package Enjoys\Forms\Renderer\Bootstrap4
 */
class Bootstrap4CheckboxRender extends CheckboxRender
{

    /**
     * @param Element $element
     * @return string
     */
    protected function renderRadio(Element $element): string
    {
        $return = '';
        /** @var Checkbox $element */
        foreach ($element->getElements() as $data) {
            $data->addClass('custom-control-input');
            $data->addClass('custom-control-label', Form::ATTRIBUTES_LABEL);

            if (empty($data->getLabel())) {
                $data->addClass('position-static');
            }

            $element->addClass('custom-control custom-checkbox', Form::ATTRIBUTES_FILLABLE_BASE);

            $return .= "<div{$element->getAttributesString(Form::ATTRIBUTES_FILLABLE_BASE)}>";
            $return .= $this->renderBody($data);
            $return .= '</div>';
        }
        return $return;
    }
}
