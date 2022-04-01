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

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Form;

/**
 * Class Elements
 *
 * @author Enjoys
 */
class BaseElement implements ElementRenderInterface
{

    protected Element $element;

    public function __construct(Element $element)
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
     *
     * @param Element $element
     * @param string $containerTag
     * @return string
     */
    protected function renderDescription(Element $element, string $containerTag = 'small'): string
    {
        if (!method_exists($element, 'getDescription') || empty($element->getDescription())) {
            return '';
        }
        return "<{$containerTag}{$element->getAttributesString(Form::ATTRIBUTES_DESC)}>{$element->getDescription()}</{$containerTag}>";
    }

    /**
     *
     * @param Element $element
     * @param string $containerTag
     * @return string
     */
    protected function renderValidation(Element $element, string $containerTag = 'div'): string
    {
        if (!method_exists($element, 'isRuleError') || !$element->isRuleError()) {
            return '';
        }
        /** @var Text $element */
        return "<{$containerTag}{$element->getAttributesString(Form::ATTRIBUTES_VALIDATE)}>{$element->getRuleErrorMessage()}</{$containerTag}>";
    }

    /**
     *
     * @param Element $element
     * @param string $star
     * @return string
     */
    protected function renderLabel(Element $element, string $star = "&nbsp;<sup>*</sup>"): string
    {
        if (empty($element->getLabel())) {
            return '';
        }

        if (!method_exists($element, 'isRequired') || !$element->isRequired()) {
            $star = "";
        }

        if (null !== $idAttribute = $element->getAttr('id')) {
            $element->setAttr($idAttribute->withName('for'), Form::ATTRIBUTES_LABEL);
        }

        return "<label{$element->getAttributesString(Form::ATTRIBUTES_LABEL)}>{$element->getLabel()}{$star}</label>";
    }

    protected function renderBody(Element $element): string
    {
        return $element->baseHtml();
    }
}
