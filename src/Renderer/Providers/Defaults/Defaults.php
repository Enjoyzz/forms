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

namespace Enjoys\Forms\Renderer\Providers\Defaults;

use Enjoys\Forms\Interfaces;

/**
 * Class Defaults
 *
 * @author Enjoys
 */
class Defaults extends \Enjoys\Forms\Renderer implements Interfaces\Renderer
{

    private $elements = [];
    private $open_header = false;
    private $count_valid_element = 0;
    private $close_headertag_after = 0;

    public function __construct(\Enjoys\Forms\Form $form)
    {
        parent::__construct($form);

        $this->setElements($this->getForm()->getElements());
    }

    public function header()
    {
        return "<form{$this->form->getAttributes()}>\n";
    }

    public function footer()
    {
        $html = '';
        if ($this->open_header === true) {
            $html .= "\t</fieldset>\n";
        }
        $html .= "</form>";
        return $html;
    }

    public function hidden()
    {
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

    public function elements(?array $elements = null)
    {
        $elements ??= $this->elements;
        
        $html = '';
        /** @var \Enjoys\Forms\Element $element */
        foreach ($elements as $key => $element) {
            unset($elements[$key]);

            if (!($element instanceof \Enjoys\Forms\Element)) {
                continue;
            }

            $this->count_valid_element++;
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
                    $html .= $this->renderInput($element);
                    break;
                case 'Enjoys\Forms\Elements\Image':
                case 'Enjoys\Forms\Elements\Submit':
                case 'Enjoys\Forms\Elements\Reset':
                    $html .= $this->renderInputButton($element);
                    break;
                case 'Enjoys\Forms\Elements\Header':
                    $html .= $this->renderHeader($element);
                    break;
                case 'Enjoys\Forms\Elements\Radio':
                case 'Enjoys\Forms\Elements\Checkbox':
                    $html .= $this->renderRadioCheckbox($element);
                    break;
                case 'Enjoys\Forms\Elements\Select':
                    $html .= $this->renderSelect($element);
                    break;
                case 'Enjoys\Forms\Elements\Textarea':
                    $html .= $this->renderTextarea($element);
                    break;
                case 'Enjoys\Forms\Elements\Button':
                    $html .= $this->renderButton($element);
                    break;
                case 'Enjoys\Forms\Elements\Datalist':
                    $html .= $this->renderDatalist($element);
                    break;
                case 'Enjoys\Forms\Elements\Captcha':
                    $html .= $this->renderCaptcha($element);
                    break;
                case 'Enjoys\Forms\Elements\Group':
                    $html .= $this->renderGroup($element);
                    break;
                default:
                    break;
            }
            if ($this->count_valid_element == $this->close_headertag_after) {
                $html .= $this->renderHeaderCloseTag();
            }
        }

        return $html;
    }

    private function renderCaptcha($element)
    {
        $html = '';
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>";
        $html .= "<br>" . $element->renderHtml();
        if (!empty($element->getDescription())) {
            $html .= "\t<br><small>{$element->getDescription()}</small><br>\n";
        }
        return $html;
    }

    private function renderInput(\Enjoys\Forms\Element $element)
    {
        $html = '';
        if ($element->isRuleError()) {
            $html .= "<p style=\"color: red\">{$element->getRuleErrorMessage()}</p>";
        }
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>
\t<input type=\"{$element->getType()}\"{$element->getAttributes()}><br>\n";
        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "\n";
    }

    private function renderRadioCheckbox(Interfaces\RadioCheckbox $element)
    {
        $html = '';
        if ($element->isRuleError()) {
            $html .= "<p style=\"color: red\">{$element->getRuleErrorMessage()}</p>";
        }
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>";


        /** @var \Enjoys\Forms\Element $data */
        foreach ($element->getElements() as $data) {
            $html .= "\t<input type=\"{$data->getType()}\" name=\"{$element->getName()}\"{$data->getAttributes()}><label for=\"{$data->getId()}\"{$data->getLabelAttributes()}>{$data->getTitle()}</label><br>\n";
        }

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "\n";
    }

    private function renderSelect(\Enjoys\Forms\Elements\Select $element)
    {
        $html = '';
        if ($element->isRuleError()) {
            $html .= "<p style=\"color: red\">{$element->getRuleErrorMessage()}</p>";
        }
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>
\t<select{$element->getAttributes()}><br>\n";


        /** @var \Enjoys\Forms\Elements\Option $option */
        foreach ($element->getElements() as $option) {
            $html .= "\t<option{$option->getAttributes()}>{$option->getTitle()}</option><br>\n";
        }
        $html .= "</select>";

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "<br>\n";
    }

    private function renderDatalist(\Enjoys\Forms\Elements\Datalist $element)
    {
        $html = "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>
\t<input {$element->getAttributes()}><datalist id=\"{$element->getAttribute('list')}\">\n";


        /** @var \Enjoys\Forms\Elements\Option $option */
        foreach ($element->getElements() as $option) {
            $html .= "\t<option value=\"{$option->getTitle()}\">\n";
        }
        $html .= "</datalist>";

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "\n";
    }

    private function renderTextarea(\Enjoys\Forms\Elements\Textarea $element)
    {
        $html = "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>
\t<textarea{$element->getAttributes()}>{$element->getValue()}</textarea><br>\n";

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "\n";
    }

    private function renderButton(\Enjoys\Forms\Elements\Button $element)
    {
        $html = "\t<button{$element->getAttributes()}>{$element->getTitle()}</button><br>\n";

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "\n";
    }

    private function renderInputButton(\Enjoys\Forms\Element $element)
    {
        return "\t<br><br><input type=\"{$element->getType()}\"{$element->getAttributes()}>\n";
    }

    private function renderHeader(\Enjoys\Forms\Elements\Header $element)
    {
        if ($element->getCloseAfterCountElements() > 0) {
            $this->close_headertag_after = $this->count_valid_element + $element->getCloseAfterCountElements();
        }
        $html = '';
        if ($this->open_header === true) {
            $html .= "\t</fieldset>";
        }
        $html .= "\t<fieldset{$element->getFieldsetAttributes()}>\n\t<legend{$element->getAttributes()}>{$element->getTitle()}</legend>\n";
        $this->open_header = true;
        return $html;
    }

    
    private function renderGroup(\Enjoys\Forms\Elements\Group $element)
    {
        $html = '';
//        if ($element->isRuleError()) {
//            $html .= "<p style=\"color: red\">{$element->getRuleErrorMessage()}</p>";
//        }
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getLabelAttributes()}>{$element->getTitle()}</label><br>\n";


        /** @var \Enjoys\Forms\Elements\Option $option */
        foreach ($element->getElements() as $element) {
            $html .=  $this->elements([$element]);
        }
//        $html .= "</select>";

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "<br>\n";
    }    
    
    private function renderHeaderCloseTag()
    {
        $this->open_header = false;
        return "\t</fieldset>\n";
    }

    public function __toString()
    {
        $html = '';
        $html .= $this->header();
        $html .= $this->hidden();
        $html .= $this->elements($this->elements);
        $html .= $this->footer();
        return $html;
    }

    public function setElements(array $elements)
    {
        $this->elements = $elements;
    }
}
