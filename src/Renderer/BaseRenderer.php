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

use Enjoys\Forms\Form;

/**
 * Description of BaseRenderer
 *
 * @author Enjoys
 */
class BaseRenderer implements RendererInterface
{

    protected Form $form;
    protected array $elements = [];

    /**
     * 
     * @param Form $form
     * @return void
     */
    public function setForm(Form $form): void
    {
        $this->form = $form;
        $this->elements = $this->form->getElements();
    }

    /**
     * 
     * @return string
     */
    protected function hiddenRender(): string
    {
        $html = '';
        /** @var \Enjoys\Forms\Element $element */
        foreach ($this->elements as $key => $element) {
            if ($element instanceof \Enjoys\Forms\Elements\Hidden) {
                unset($this->elements[$key]);
                $html .= $element->baseHtml();
            }
            continue;
        }
        return $html;
    }

    /**
     * 
     * @param array|null $elements
     * @return string
     */
    protected function elementsRender(?array $elements = null): string
    {
        $elements ??= $this->elements;
        $html = '';
        /** @var \Enjoys\Forms\Element $element */
        foreach ($elements as $key => $element) {
            unset($elements[$key]);

            if (!($element instanceof \Enjoys\Forms\Element)) {
                continue;
            }

            $html .= $this->elementRender($element);
        }
        return $html;
    }

    /**
     * 
     * @param \Enjoys\Forms\Element $element
     * @return string
     */
    public function elementRender(\Enjoys\Forms\Element $element): string
    {
        $elementRender = new BaseElementRender($element);
        return $elementRender->render();
    }

    /**
     * 
     * @return string
     */
    public function render(): string
    {
        return "<form{$this->form->getAttributesString()}>"
                . $this->hiddenRender()
                . $this->elementsRender($this->elements)
                . "</form>";
    }
}
