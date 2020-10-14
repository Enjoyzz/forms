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

namespace Enjoys\Forms\Renderer\Prepare;

/**
 * Class Elements
 *
 * @author Enjoys
 */
class Elements
{

    private $elements = [];

    public function __construct()
    {
        
    }
    
    public function getElements()
    {
        return $this->elements;
    }

    public function addElement(\Enjoys\Forms\Element $element)
    {
        switch (\get_class($element)) {
            case 'Enjoys\Forms\Elements\Text':
            case 'Enjoys\Forms\Elements\Password':
            case 'Enjoys\Forms\Elements\Color':
            case 'Enjoys\Forms\Elements\Date':
            case 'Enjoys\Forms\Elements\Datetime':
            case 'Enjoys\Forms\Elements\Datetimelocal':
            case 'Enjoys\Forms\Elements\Email':
            case 'Enjoys\Forms\Elements\Number':
            case 'Enjoys\Forms\Elements\Range':
            case 'Enjoys\Forms\Elements\Search':
            case 'Enjoys\Forms\Elements\Tel':
            case 'Enjoys\Forms\Elements\Time':
            case 'Enjoys\Forms\Elements\Url':
            case 'Enjoys\Forms\Elements\Month':
            case 'Enjoys\Forms\Elements\Week':
            case 'Enjoys\Forms\Elements\File':
                $this->elements[] = $this->prepareInput($element);
                break;
            case 'Enjoys\Forms\Elements\Image':
            case 'Enjoys\Forms\Elements\Submit':
            case 'Enjoys\Forms\Elements\Reset':
                $this->elements[] = $this->prepareInputButton($element);
                break;
            case 'Enjoys\Forms\Elements\Header':
                $this->elements[] = $this->prepareHeader($element);
                break;
            case 'Enjoys\Forms\Elements\Radio':
            case 'Enjoys\Forms\Elements\Checkbox':
                $this->elements[] = $this->prepareRadioCheckbox($element);
                break;
            case 'Enjoys\Forms\Elements\Select':
                $this->elements[] = $this->prepareSelect($element);
                break;
            case 'Enjoys\Forms\Elements\Textarea':
                $this->elements[] = $this->prepareTextarea($element);
                break;
            case 'Enjoys\Forms\Elements\Button':
                $this->elements[] = $this->prepareButton($element);
                break;
            case 'Enjoys\Forms\Elements\Datalist':
                $this->elements[] = $this->prepareDatalist($element);
                break;
            case 'Enjoys\Forms\Elements\Captcha':
                $this->elements[] = $this->prepareCaptcha($element);
                break;
            case 'Enjoys\Forms\Elements\Group':
                $this->elements[] = $this->prepareGroup($element);
                break;
            default:
                break;
        }
    }

    private function prepareInput($element)
    {
       // dump($element);
        $element->addAttributes([
            'class' => 'test test2'
        ]);
        return [
            
            'error' => ($element->isRuleError()) ? $element->getRuleErrorMessage() : null,
            'label' => "<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label>",
            'body' => "<input type=\"{$element->getType()}\"{$element->getAttributes()}>",
            'decription' => (!empty($element->getDescription())) ? $element->getDescription() : null
        ];
    }

    private function prepareInputButton($element)
    {
        
    }

    private function prepareHeader($element)
    {
        
    }

    private function prepareRadioCheckbox($element)
    {
        
    }

    private function prepareSelect($element)
    {
        
    }

    private function prepareTextarea($element)
    {
        
    }

    private function prepareButton($element)
    {
        
    }

    private function prepareDatalist($element)
    {
        
    }

    private function prepareCaptcha($element)
    {
        
    }
}
