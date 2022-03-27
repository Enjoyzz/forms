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
use Enjoys\Forms\Elements\Datalist;

/**
 * Description of SelectRender
 *
 * @author Enjoys
 */
class DatalistRender extends BaseElement
{

    /**
     * @return string
     */
    public function render()
    {
        return
            $this->renderLabel($this->element) .
            $this->renderDatalist($this->element) .
            $this->renderDescription($this->element) .
            $this->renderValidation($this->element) .
            '';
    }

    protected function renderDatalist(Element $element): string
    {
        /**
         * @psalm-suppress PossiblyInvalidArgument
         */
        $return = sprintf(
            '<input%s><datalist id="%s">',
            $element->getAttributesString(),
            $element->getAttribute('list')
        );

        /** @var Datalist $element */
        foreach ($element->getElements() as $data) {
            //$return .= "<option value=\"{$data->getLabel()}\">";
            $return .= $this->renderBody($data);
        }
        $return .= "</datalist>";
        return $return;
    }
}
