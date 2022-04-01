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

namespace Enjoys\Forms\Renderer\Bootstrap4;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\BaseRenderer;
use Enjoys\Forms\Renderer\RendererInterface;
use Enjoys\Traits\Options;

/**
 * Description of Bootstrap4
 *
 * @author Enjoys
 */
class Bootstrap4 extends BaseRenderer implements RendererInterface
{
    use Options;

    public function __construct(array $options = [], Form $form = null)
    {
        parent::__construct($form);
        $this->setOptions($options);
    }

    public function elementRender(Element $element): string
    {

        if (method_exists($element, 'getDescription') && !empty($element->getDescription())) {
            $element->setAttrs(Attribute::createFromArray([
                'id' => (is_string($element->getAttr('id')->getValueString()) ?: '' ). 'Help',
                'class' => 'form-text text-muted'
            ]), Form::ATTRIBUTES_DESC);

            $element->setAttrs(Attribute::createFromArray([
                'aria-describedby' => is_string( $element->getAttr('id', Form::ATTRIBUTES_DESC)->getValueString()) ?: ''
            ]));
        }

        if (method_exists($element, 'isRuleError') && $element->isRuleError()) {
            $element->setAttrs(Attribute::createFromArray([
                'class' => 'is-invalid'
            ]));
            $element->setAttrs(Attribute::createFromArray([
                'class' => 'invalid-feedback d-block'
            ]), Form::ATTRIBUTES_VALIDATE);
        }

        $elementRender = new Bootstrap4ElementRender($element);
        return $elementRender->render();
    }
}
