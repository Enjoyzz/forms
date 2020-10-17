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
class RenderBase
{

//    protected $description = null;
//    protected $body = null;
//    protected $validation = null;
//    protected $label = null;

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

    protected function renderLabel($element)
    {
        if (empty($element->getTitle())) {
            return;
        }

        return "<label for=\"{$element->getId()}\"{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$element->getTitle()}</label>";
    }

    protected function renderBody($element)
    {

        switch (\get_class($element)) {
            case 'Enjoys\Forms\Elements\Text':
            case 'Enjoys\Forms\Elements\Password':
            case 'Enjoys\Forms\Elements\Color':
            case 'Enjoys\Forms\Elements\Date':
            case 'Enjoys\Forms\Elements\Datetime':
            case 'Enjoys\Forms\Elements\Datetimelocal':
            case 'Enjoys\Forms\Elements\Email':
            case 'Enjoys\Forms\Elements\Number':
            case 'Enjoys\Forms\Elements\Range':
            case 'Enjoys\Forms\Elements\Search':
            case 'Enjoys\Forms\Elements\Tel':
            case 'Enjoys\Forms\Elements\Time':
            case 'Enjoys\Forms\Elements\Url':
            case 'Enjoys\Forms\Elements\Month':
            case 'Enjoys\Forms\Elements\Week':
            case 'Enjoys\Forms\Elements\File':
            case 'Enjoys\Forms\Elements\Radio':
            case 'Enjoys\Forms\Elements\Checkbox':
                return "<input type=\"{$element->getType()}\"{$element->getAttributes()}>";
            case 'Enjoys\Forms\Elements\Textarea':
                return "<textarea{$element->getAttributes()}>{$element->getValue()}</textarea>";
            case 'Enjoys\Forms\Elements\Button':
                return "<button{$element->getAttributes()}>{$element->getTitle()}</button>";
            case 'Enjoys\Forms\Elements\Option':
                return "<option{$element->getAttributes()}>{$element->getTitle()}</option>";
            case 'Enjoys\Forms\Elements\Captcha':
                return $element->renderHtml();
            case 'Enjoys\Forms\Elements\Header':
                return $element->getTitle();
            default:
                return;
        }
    }
}
