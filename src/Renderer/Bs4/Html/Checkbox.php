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
 * Description of Checkbox
 *
 * @author deadl
 */
class Checkbox extends Radio
{

    public function render()
    {
        return
                $this->renderLabel($this->element) .
                (($this->renderOptions['custom-checkbox'] === true) ? $this->renderCustomCheckbox($this->element) : $this->renderRadio($this->element)) .
                $this->renderDescription($this->element) .
                $this->renderValidation($this->element) .
                '';
    }

    protected function renderCustomCheckbox($element)
    {
        $return = '';
        foreach ($element->getElements() as $data) {
            $data->addClass('custom-control-input');
            $data->addClass('custom-control-label', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
            $data->setAttributes([
                'name' => $element->getName()
            ]);

            if (empty($data->getTitle())) {
                $data->addClass('position-static');
            }

            $data->addClass('custom-control custom-checkbox', 'checkBox');
            if ($this->renderOptions['checkbox_inline'] === true) {
                $data->addClass('custom-control-inline', 'checkBox');
            }

            if ($element->isRuleError()) {
                $data->addClass('is-invalid');
            }

            $return .= "<div{$data->getAttributes('checkBox')}>";
            $return .= $this->renderBody($data);
            $return .= $this->renderLabel($data);
            $return .= '</div>';
        }
        return $return;
    }
}
