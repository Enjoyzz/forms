<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Bootstrap4;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\ElementsRender\RadioRender;

/**
 * Class Bootstrap4RadioRender
 * @package Enjoys\Forms\Renderer\Bootstrap4
 */
class Bootstrap4RadioRender extends RadioRender
{

    /**
     * @param Element $element
     * @return string
     */
    protected function renderRadio(Element $element): string
    {
        $return = '';
        /** @var Radio $element */
        foreach ($element->getElements() as $data) {
            $data->addClass('custom-control-input');
            $data->addClass('custom-control-label', Form::ATTRIBUTES_LABEL);

            if (empty($data->getLabel())) {
                $data->addClass('position-static');
            }

            $element->addClass('custom-control custom-radio', Form::ATTRIBUTES_FILLABLE_BASE);

            $return .= "<div{$element->getAttributesString(Form::ATTRIBUTES_FILLABLE_BASE)}>";
            $return .= $this->renderBody($data);
            $return .= '</div>';
        }
        return $return;
    }
}
