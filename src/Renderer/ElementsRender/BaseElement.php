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

namespace Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Form;
use Enjoys\Forms\ElementInterface;

/**
 * Class Elements
 *
 * @author Enjoys
 */
class BaseElement implements ElementRenderInterface
{

    protected ElementInterface $element;

    public function __construct(\Enjoys\Forms\Element $element)
    {
        $this->element = $element;
    }

    /**
     * @return string
     */
    public function render()
    {
        return
                $this->renderLabel($this->element) .
                $this->renderBody($this->element) .
                $this->renderDescription($this->element) .
                $this->renderValidation($this->element) .
                '';
    }

    /**
     * @return null|string
     */
    protected function renderDescription(\Enjoys\Forms\Element $element, $containerTag = 'small')
    {
        if (!method_exists($element, 'getDescription') || empty($element->getDescription())) {
            return;
        }
        return "<{$containerTag}{$element->getAttributesString(Form::ATTRIBUTES_DESC)}>{$element->getDescription()}</{$containerTag}>";
    }

    /**
     * @return null|string
     */
    protected function renderValidation(\Enjoys\Forms\Element $element, $containerTag = 'div')
    {
        if (!method_exists($element, 'isRuleError') || !$element->isRuleError()) {
            return;
        }
        return "<{$containerTag}{$element->getAttributesString(Form::ATTRIBUTES_VALIDATE)}>{$element->getRuleErrorMessage()}</{$containerTag}>";
    }

    /**
     * @return null|string
     */
    protected function renderLabel(\Enjoys\Forms\Element $element, $star = "&nbsp;<sup>*</sup>")
    {
        if (empty($element->getLabel())) {
            return;
        }

        if (!method_exists($element, 'isRequired') || !$element->isRequired()) {
            $star = "";
        }

        if ($element->getAttribute('id') !== false) {
            $element->setAttributes([
                'for' => $element->getAttribute('id')
                    ], Form::ATTRIBUTES_LABEL);
        }

        return "<label{$element->getAttributesString(Form::ATTRIBUTES_LABEL)}>{$element->getLabel()}{$star}</label>";
    }

    protected function renderBody(\Enjoys\Forms\Element $element): ?string
    {
        return $element->baseHtml();
    }
}
