<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Bs4\Html;

/**
 * Description of Radio
 *
 * @author deadl
 */
class HtmlRadio extends \Enjoys\Forms\Renderer\LayoutBase
{

    public function __construct(\Enjoys\Forms\Element $element, $renderOptions = array())
    {
        parent::__construct($element, $renderOptions);
        if ($this->renderOptions['checkbox_inline'] === true) {
            $this->element->addClass('d-block', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
        }
    }

    public function render()
    {
        return
                $this->renderLabel($this->element) .
                (($this->renderOptions['custom-radio'] === true) ? $this->renderCustomRadio($this->element) : $this->renderRadio($this->element)) .
                $this->renderDescription($this->element) .
                $this->renderValidation($this->element) .
                '';
    }

    protected function renderRadio($element)
    {
        $return = '';
        foreach ($element->getElements() as $data) {
            $data->addClass('form-check-input');
            $data->addClass('form-check-label', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
            $data->setAttributes([
                'name' => $element->getName()
            ]);

            if (empty($data->getLabel())) {
                $data->addClass('position-static');
            }

            $data->addClass('form-check', 'checkBox');
            if ($this->renderOptions['checkbox_inline'] === true) {
                $data->addClass('form-check-inline', 'checkBox');
            }

            if ($element->isRuleError()) {
                $data->addClass('is-invalid');
            }

            $return .= "<div{$data->getAttributesString('checkBox')}>";
            $return .= $this->renderBody($data);
            $return .= $this->renderLabel($data);
            $return .= '</div>';
        }
        return $return;
    }

    protected function renderCustomRadio($element)
    {
        $return = '';
        foreach ($element->getElements() as $data) {
            $data->addClass('custom-control-input');
            $data->addClass('custom-control-label', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
            $data->setAttributes([
                'name' => $element->getName()
            ]);

            if (empty($data->getLabel())) {
                $data->addClass('position-static');
            }

            $data->addClass('custom-control custom-radio', 'checkBox');
            if ($this->renderOptions['checkbox_inline'] === true) {
                $data->addClass('custom-control-inline', 'checkBox');
            }

            if ($element->isRuleError()) {
                $data->addClass('is-invalid');
            }

            $return .= "<div{$data->getAttributesString('checkBox')}>";
            $return .= $this->renderBody($data);
            $return .= $this->renderLabel($data);
            $return .= '</div>';
        }
        return $return;
    }
}
