<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer;

/**
 * Description of RenderLabel
 *
 * @author Enjoys
 */
class RenderLabel
{

    private $element;
    private $star = "";

    public function __construct($element, $star = "&nbsp;<sup>*</sup>")
    {
        $this->element = $element;
//        if ($this->element->isRequired()) {
//            $this->star = $star;
//        }
    }

    public function __toString()
    {
        if (empty($this->element->getTitle())) {
            return '';
        }

        $this->element->setAttributes([
            'for' => $this->element->getAttribute('id')
                ], \Enjoys\Forms2\Form::ATTRIBUTES_LABEL);
        return "<label{$this->element->getAttributes(\Enjoys\Forms2\Form::ATTRIBUTES_LABEL)}>{$this->element->getTitle()}{$this->star}</label>";
    }
}
