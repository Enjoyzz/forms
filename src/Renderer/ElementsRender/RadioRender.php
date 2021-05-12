<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;

/**
 * Class RadioRender
 * @package Enjoys\Forms\Renderer\ElementsRender
 */
class RadioRender extends BaseElement
{

    /**
     * @return string
     */
    public function render()
    {
        return
            $this->renderLabel($this->element) .
            $this->renderRadio($this->element) .
            $this->renderDescription($this->element) .
            $this->renderValidation($this->element) .
            '';
    }

    /**
     * @param Element $element
     * @return string
     */
    protected function renderRadio(Element $element): string
    {
        $return = '';
        /** @var Radio $element */
        foreach ($element->getElements() as $data) {
            if ($element->isRuleError()) {
                $data->addClass('is-invalid');
            }

            $return .= "<div{$element->getAttributesString(Form::ATTRIBUTES_FILLABLE_BASE)}>";
            $return .= $this->renderBody($data);
            $return .= '</div>';
        }
        return $return;
    }
}
