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

/**
 * Description of ElementRender
 *
 * @author Enjoys
 */
class ElementRender extends \Enjoys\Forms\Renderer\BaseElementRender
{

    public function __construct(\Enjoys\Forms\ElementInterface $element)
    {
        switch (\get_class($element)) {
//            case \Enjoys\Forms\Elements\File::class:
//                $this->elementRender = new \Enjoys\Forms\Renderer\ElementsRender\FileRender($element);
//                break;
//            case \Enjoys\Forms\Elements\Header::class:
//                $this->elementRender = new \Enjoys\Forms\Renderer\ElementsRender\HeaderRender($element);
//                break;
         //   case \Enjoys\Forms\Elements\Radio::class:

//                foreach ($element->getElements() as $key => $data) {
//                    $data->addClass('custom-control-input');
//                    $data->addClass('custom-control-label', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
////                    $data->setAttributes([
////                        'name' => $element->getName()
////                    ]);
//
//                    if (empty($data->getLabel())) {
//                        $data->addClass('position-static');
//                    }
//
//                    $data->addClass('custom-control custom-radio', 'checkBox');
//                    if ($this->renderOptions['checkbox_inline'] === true) {
//                        $data->addClass('custom-control-inline', 'checkBox');
//                    }
//
//                    if ($element->isRuleError()) {
//                        $data->addClass('is-invalid');
//                    }
//                    
//                    $element->updateElement($key, $data);
//                }
                //$this->elementRender = new Bootstrap4RadioRender($element);
//                break;
            default:
//                $this->elementRender = new \Enjoys\Forms\Renderer\ElementsRender\InputRender($element);
                parent::__construct($element);
                break;
        }
    }

    public function render()
    {
        $html = '<div class="form-group">';
        $html .= $this->elementRender->render();
        $html .= '</div>';
        return $html;
    }
}
