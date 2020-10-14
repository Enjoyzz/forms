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

namespace Enjoys\Forms\Renderer\Providers\Bs4\Prepare;

/**
 * Class Input
 *
 * @author Enjoys
 */
class Input
{

    private $description = null;
    private $body = null;
    private $validation = null;
    private $label = null;
    private $el;

    public function __construct(\Enjoys\Forms\Element $element)
    {
        $this->el = $element;

        $this->description();
        $this->validation();
        $this->label();
        $this->body();

    }
    
    public function __get($name)
    {
        if(property_exists($this, $name)){
            return $this->$name;
        }
        return null;
    }
    
    private function label()
    {
        $this->label = "<label for=\"{$this->el->getId()}\"{$this->el->getLabelAttributes()}>{$this->el->getTitle()}</label>";
    }

    private function validation()
    {
    
        if ($this->el->isRuleError()) {
            $this->el->addAttributes([
                'class' => 'is-invalid'
            ]);
            $this->el->addValidAttributes([
                'class' => 'invalid-feedback'
            ]);
            $this->validation =  "<div{$this->el->getValidAttributes()}>{$this->el->getRuleErrorMessage()}</div>";
        }
       
    }

    private function body()
    {
        $this->el->addAttributes('class', 'form-control');
        $this->body = "<input type=\"{$this->el->getType()}\"{$this->el->getAttributes()}>";
    }

    private function description()
    {
  
        if (!empty($this->el->getDescription())) {
            $this->el->addDescAttributes([
                'id' => $this->el->getId() . 'Help',
                'class' => 'form-text text-muted'
            ]);

            $this->el->addAttributes([
                'aria-describedby' => $this->el->getDescAttribute('id')
            ]);

            $this->description =  "<small{$this->el->getDescAttributes()}>{$this->el->getDescription()}</small>";
        }
    }
}
