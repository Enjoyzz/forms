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

/**
 * Class Elements
 *
 * @author Enjoys
 */
class Prepare
{

//    protected $description = null;
//    protected $body = null;
//    protected $validation = null;
//    protected $label = null;
//
//    /**
//     * @var \Enjoys\Forms\Element 
//     */
//    protected \Enjoys\Forms\Element $el;
//    protected array $rendererOptions = [];
//
//    public function __construct(\Enjoys\Forms\Element $element, $rendererOptions)
//    {
//        $this->el = $element;
//        $this->rendererOptions = $rendererOptions;
//
//        $this->description();
//        $this->validation();
//        $this->label();
//        $this->body();
//    }
//
//    public function __get($name)
//    {
//        if (property_exists($this, $name)) {
//            return $this->$name;
//        }
//        return null;
//    }
//    
//    protected function body()
//    {
//        
//    }

    public static function getHtmlDescription(\Enjoys\Forms\Element $element, $containerTag = 'small', $extra_before = '', $extra_after = '')
    {
        if (empty($element->getDescription())) {
            return;
        }
        return "<{$containerTag}{$element->getDescAttributes()}>{$extra_before}{$element->getDescription()}{$extra_after}</{$containerTag}>";
    }

    public static function getHtmlValidation(\Enjoys\Forms\Element $element, $containerTag = 'div', $extra_before = '', $extra_after = '')
    {
        if (!$element->isRuleError()) {
            return;
        }
        return "<{$containerTag}{$element->getValidAttributes()}>{$extra_before}{$element->getRuleErrorMessage()}{$extra_after}</{$containerTag}>";
    }

    public static function getHtmlLabel(\Enjoys\Forms\Element $element, $extra_before = '', $extra_after = '')
    {
        if (empty($element->getTitle())) {
            return;
        }

        return "<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$extra_before}{$element->getTitle()}{$extra_after}</label>";
    }

    public static function getHtmlBody(\Enjoys\Forms\Element $element, $extra_before = '', $extra_after = '')
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
            case 'Enjoys\Forms\Elements\Radio':
            case 'Enjoys\Forms\Elements\Checkbox':
                return "{$extra_before}<input type=\"{$element->getType()}\"{$element->getAttributes()}>{$extra_after}";
            case 'Enjoys\Forms\Elements\Textarea':
                return "{$extra_before}<textarea{$element->getAttributes()}>{$element->getValue()}</textarea>{$extra_after}";
            case 'Enjoys\Forms\Elements\Button':
                return "{$extra_before}<button{$element->getAttributes()}>{$element->getTitle()}</button>{$extra_after}";
            default:
                return;
        }
    }
}
