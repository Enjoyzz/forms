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

/**
 * Class Defaults
 *
 * @author Enjoys
 */
class Defaults extends \Enjoys\Forms\Renderer {

    private $elements = [];

    public function __construct(\Enjoys\Forms\Forms $form) {
        parent::__construct($form);

        $this->elements = $this->form->getElements();

        $this->header();
        $this->hidden();
        $this->elements();
        $this->footer();
        dump($this->elements);
    }

    private function header() {
        $this->html .= "<form{$this->form->getAttributes()}>\n";
    }

    private function footer() {
        $this->html .= "</form>";
    }

    private function hidden() {
        /** @var \Enjoys\Forms\Element $element */
        foreach ($this->elements as $key => $element) {
            if (!($element instanceof \Enjoys\Forms\Elements\Hidden)) {
                continue;
            }
            $this->html .= "<input type=\"{$element->getType()}\"{$element->getAttributes()}>\n";
            unset($this->elements[$key]);
        }
    }

    private function elements() {
        /** @var \Enjoys\Forms\Element $element */
        foreach ($this->elements as $key => $element) {
            if ($element instanceof \Enjoys\Forms\Elements\Hidden) {
                continue;
            }

            if (
                    $element instanceof \Enjoys\Forms\Elements\Text ||
                    $element instanceof \Enjoys\Forms\Elements\Password
            ) {
                $this->renderInput($element);
            }

            if (
                    $element instanceof \Enjoys\Forms\Elements\Submit
            ) {
                $this->renderButton($element);
            }

            unset($this->elements[$key]);
        }
    }

    private function renderInput(\Enjoys\Forms\Element $element) {
        $this->html .= "
            <label for=\"{$element->getId()}\">{$element->getTitle()}</label>
            <input type=\"{$element->getType()}\"{$element->getAttributes()}>
            
            ";
    }
    
    private function renderButton(\Enjoys\Forms\Element $element) {
        $this->html .= "
            <input type=\"{$element->getType()}\"{$element->getAttributes()}>
            ";
    }    

    public function __toString() {
        return $this->html;
    }

}
