<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer\Bootstrap4;

/**
 * Description of RenderCheckbox
 *
 * @author Enjoys
 */
class RenderCheckbox
{

    private $element;

    public function __construct(\Enjoys\Forms2\Element $element)
    {
        $this->element = $element;
    }

    public function __toString()
    {
        $return = '';
        foreach ($this->element->getElements() as $data) {
            $data->addClass('custom-control-input');
            $data->addClass('custom-control-label', \Enjoys\Forms2\Form::ATTRIBUTES_LABEL);
//            $data->setAttributes([
//                'name' => $element->getName()
//            ]);

            if (empty($data->getTitle())) {
                $data->addClass('position-static');
            }

            $data->addClass('custom-control custom-checkbox', 'checkBox');
//            if ($this->renderOptions['checkbox_inline'] === true) {
//                $data->addClass('custom-control-inline', 'checkBox');
//            }

//            if ($element->isRuleError()) {
//                $data->addClass('is-invalid');
//            }

            $return .= "<div{$data->getAttributes('checkBox')}>";
            $return .= $data->baseHtml();
            $return .= (string) new \Enjoys\Forms2\Renderer\RenderLabel($data);
            $return .= '</div>';
        }
        return $return;
    }
}
