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

namespace Enjoys\Forms\Renderer;

use Enjoys\Forms\Interfaces;

/**
 * Class Defaults
 *
 * @author Enjoys
 */
class Defaults extends \Enjoys\Forms\Renderer implements Interfaces\Renderer {

    private $elements = [];

    public function __construct(\Enjoys\Forms\Forms $form) {
        parent::__construct($form);

        $this->setElements($this->getForm()->getElements()) ;


    }


    public function header() {
        return "<form{$this->form->getAttributes()}>\n";
    }

    public function footer() {
        return "</form>";
    }

    public function hidden() {
        $html = '';
        /** @var \Enjoys\Forms\Element $element */
        foreach ($this->elements as $key => $element) {
            if ($element instanceof \Enjoys\Forms\Elements\Hidden) {
                $html .= "<input type=\"{$element->getType()}\"{$element->getAttributes()}>\n";
                unset($this->elements[$key]);
            }
            continue;
        }
        return $html;
    }

    public function elements() {
        $html = '';
        /** @var \Enjoys\Forms\Element $element */
        foreach ($this->elements as $key => $element) {
            unset($this->elements[$key]);
            
            if(!is_object($element)){
                continue;
            }
            
            switch (\get_class($element)) {
                case 'Enjoys\Forms\Elements\Text':
                case 'Enjoys\Forms\Elements\Password':
                    $html .= $this->renderInput($element);
                    break;
                case 'Enjoys\Forms\Elements\Submit':
                    $html .= $this->renderButton($element);
                    break;
                default:
                    break;
            }
            
        }
        
        return $html;
    }

    private function renderInput(\Enjoys\Forms\Element $element) {
        return "\t<label for=\"{$element->getId()}\">{$element->getTitle()}</label>
\t<input type=\"{$element->getType()}\"{$element->getAttributes()}>\n";
    }

    private function renderButton(\Enjoys\Forms\Element $element) {
        return "\t<input type=\"{$element->getType()}\"{$element->getAttributes()}>\n";
    }

    public function __toString() {
        $html = '';
        $html .= $this->header();
        $html .= $this->hidden();
        $html .= $this->elements();
        $html .= $this->footer();
        return $html;
    }
    
    public function setElements(array $elements) {
        $this->elements = $elements;
    }

}
