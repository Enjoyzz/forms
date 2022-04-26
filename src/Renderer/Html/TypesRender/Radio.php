<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Interfaces\Ruled;

class Radio extends Input
{

    protected function bodyRender(Element $element): string
    {
        $return = '';

        /** @var Element&Fillable&Ruled  $element */
        foreach ($element->getElements() as $data) {
            if ($element->isRuleError()) {
                $data->addClass('is-invalid');
            }

            $return .= "<div{$element->getAttributesString(Form::ATTRIBUTES_FILLABLE_BASE)}>";
            $return .= parent::bodyRender($data);
            $return .= "</div>\n";
        }
        return $return;
    }
}
