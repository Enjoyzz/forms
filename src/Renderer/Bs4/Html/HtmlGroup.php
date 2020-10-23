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
 * Description of Group
 *
 * @author deadl
 */
class HtmlGroup extends \Enjoys\Forms\Renderer\LayoutBase
{
    private $renderer;
    
    public function __construct($element, $renderer)
    {
        parent::__construct($element);
        $this->renderer = $renderer;
       // $this->element->addClass('d-block', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
    }
    
    public function render()
    {
        //dump($element->elements($element->getElements()));
          $return .= $this->renderLabel($this->element);
        $return .= "<div class=\"form-row\">";
      
        foreach ($this->renderer->elements($this->element->getElements()) as $data) {
            $return .= "<div class=\"col\">{$data->render()}</div>";
        }
        
        $return .= "</div>";
        $return .= $this->renderDescription($this->element);
        return $return;
    }
}
