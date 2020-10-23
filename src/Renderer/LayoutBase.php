<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
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

namespace Enjoys\Forms\Renderer;

/**
 * Class Elements
 *
 * @author Enjoys
 */
class LayoutBase
{
    protected \Enjoys\Forms\Element $element;
    protected $renderOptions = [];

    public function __construct(\Enjoys\Forms\Element $element, $renderOptions = [])
    {
        $this->element = $element;
        $this->renderOptions = $renderOptions;
    }

    public function render()
    {
        return
                $this->renderLabel($this->element) .
                $this->renderBody($this->element) .
                $this->renderDescription($this->element) .
                $this->renderValidation($this->element) .
                '';
    }

    protected function renderDescription($element, $containerTag = 'small')
    {
        if (empty($element->getDescription())) {
            return;
        }
        return "<{$containerTag}{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_DESC)}>{$element->getDescription()}</{$containerTag}>";
    }

    protected function renderValidation($element, $containerTag = 'div')
    {
        if (!$element->isRuleError()) {
            return;
        }
        return "<{$containerTag}{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_VALIDATE)}>{$element->getRuleErrorMessage()}</{$containerTag}>";
    }

    protected function renderLabel($element, $star = "&nbsp;<sup>*</sup>")
    {
        if (empty($element->getTitle())) {
            return;
        }
        
        if (!$element->isRequired()) {
            $star = "";
        }

        return "<label for=\"{$element->getId()}\"{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$element->getTitle()}{$star}</label>";
    }

    protected function renderBody(\Enjoys\Forms\Element $element)
    {

        switch (\get_class($element)) {
            case 'Enjoys\Forms\Elements\Textarea':
                return "<textarea{$element->getAttributes()}>{$element->getValue()}</textarea>";
            case 'Enjoys\Forms\Elements\Button':
                return "<button{$element->getAttributes()}>{$element->getTitle()}</button>";
            case 'Enjoys\Forms\Elements\Option':
                return "<option{$element->getAttributes()}>{$element->getTitle()}</option>";
//            case 'Enjoys\Forms\Elements\Optgroup':
//                return;
            case 'Enjoys\Forms\Elements\Captcha':
                return $element->renderHtml();
            case 'Enjoys\Forms\Elements\Header':
                return $element->getTitle();
            default:
                return "<input type=\"{$element->getType()}\"{$element->getAttributes()}>";
        }
    }
}
