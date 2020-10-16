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

namespace Enjoys\Forms\Renderer\Providers\Bs4;

use Enjoys\Forms\Interfaces;
use Enjoys\Forms\Renderer\Prepare;

/**
 * Class Bs4
 *
 * @author Enjoys
 */
class Bs4 extends \Enjoys\Forms\Renderer implements Interfaces\Renderer
{

    private $elements = [];
    private $open_header = false;
    private $count_valid_element = 0;
    private $close_headertag_after = 0;

    // private $prepare;

    public function __construct(\Enjoys\Forms\Form $form, array $options = null)
    {
        parent::__construct($form, $options);
        // $this->prepare = new Prepare\Elements();
        $this->setElements($this->form->getElements());

        if ($this->rendererOptions['inline'] === true) {
            $this->form->addClass('form-inline');
        }
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

            // dump($element);
            //    $this->prepare->addElement($element);

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
                case 'Enjoys\Forms\Elements\Textarea':
                    $elementsHtml[] = $this->renderInput($element);
                    break;
                case 'Enjoys\Forms\Elements\File':
                    $elementsHtml[] = $this->renderFile($element);
                    break;
                case 'Enjoys\Forms\Elements\Image':
                case 'Enjoys\Forms\Elements\Submit':
                case 'Enjoys\Forms\Elements\Reset':
                    $elementsHtml[] = $this->renderInputButton($element);
                    break;
                case 'Enjoys\Forms\Elements\Header':
                    $elementsHtml[] = $this->renderHeader($element);
                    break;
                case 'Enjoys\Forms\Elements\Radio':
                case 'Enjoys\Forms\Elements\Checkbox':
                    $elementsHtml[] = $this->renderRadioCheckbox($element);
                    break;
                case 'Enjoys\Forms\Elements\Select':
                    $elementsHtml[] = $this->renderSelect($element);
                    break;

                case 'Enjoys\Forms\Elements\Button':
                    $elementsHtml[] = $this->renderButton($element);
                    break;
                case 'Enjoys\Forms\Elements\Datalist':
                    $elementsHtml[] = $this->renderDatalist($element);
                    break;
                case 'Enjoys\Forms\Elements\Captcha':
                    $elementsHtml[] = $this->renderCaptcha($element);
                    break;
                case 'Enjoys\Forms\Elements\Group':
                    $elementsHtml[] = $this->renderGroup($element);
                    break;
                default:
                    break;
            }
            if ($this->count_valid_element == $this->close_headertag_after) {
                $html .= $this->renderHeaderCloseTag();
            }
        }

        return $elementsHtml;
    }

    private function renderCaptcha($element)
    {
        $html = '';
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$element->getTitle()}</label><br>";
        $html .= "<br>" . $element->renderHtml();
        if (!empty($element->getDescription())) {
            $html .= "\t<br><small>{$element->getDescription()}</small><br>\n";
        }
        return $html;
    }

    private function renderInput(\Enjoys\Forms\Element $element)
    {
        $element->setAttributes([
            'class' => 'form-control'
        ]);

        if (!empty($element->getDescription())) {
            $element->setAttributes([
                'id' => $element->getId() . 'Help',
                'class' => 'form-text text-muted'
            ], \Enjoys\Forms\Form::ATTRIBUTES_DESC);
            $element->setAttributes([
                'aria-describedby' => $element->getAttribute('id', \Enjoys\Forms\Form::ATTRIBUTES_DESC)
            ]);
        }

        if ($element->isRuleError()) {
            $element->setAttributes([
                'class' => 'is-invalid'
            ]);
            $element->setAttributes([
                'class' => 'invalid-feedback'
            ], \Enjoys\Forms\Form::ATTRIBUTES_VALIDATE);
        }

        return Prepare::getHtmlLabel($element)
                . Prepare::getHtmlBody($element)
                . Prepare::getHtmlDescription($element)
                . Prepare::getHtmlValidation($element);
    }

    private function renderFile(\Enjoys\Forms\Element $element)
    {
        $element->setAttributes([
            'class' => 'form-control-file'
        ]);

        if (!empty($element->getDescription())) {
            $element->setAttributes([
                'id' => $element->getId() . 'Help',
                'class' => 'form-text text-muted'
            ], \Enjoys\Forms\Form::ATTRIBUTES_DESC);
            $element->setAttributes([
                'aria-describedby' => $element->getAttribute('id', \Enjoys\Forms\Form::ATTRIBUTES_DESC)
            ]);
        }

        if ($element->isRuleError()) {
            $element->setAttributes([
                'class' => 'is-invalid'
            ]);
            $element->setAttributes([
                'class' => 'invalid-feedback'
            ], \Enjoys\Forms\Form::ATTRIBUTES_VALIDATE);
        }

        return Prepare::getHtmlLabel($element)
                . Prepare::getHtmlBody($element)
                . Prepare::getHtmlDescription($element)
                . Prepare::getHtmlValidation($element);
    }

    private function renderRadioCheckbox(Interfaces\RadioCheckbox $element)
    {


        if (!empty($element->getDescription())) {
            $element->setAttributes([
                'id' => $element->getId() . 'Help',
                'class' => 'form-text text-muted'
            ], \Enjoys\Forms\Form::ATTRIBUTES_DESC);
            $element->setAttributes([
                'aria-describedby' => $element->getAttribute('id', \Enjoys\Forms\Form::ATTRIBUTES_DESC)
            ]);
        }

        if ($element->isRuleError()) {
            $element->setAttributes([
                'class' => 'invalid-feedback',
                'style' => 'display: block;'
            ], \Enjoys\Forms\Form::ATTRIBUTES_VALIDATE);
        }
        $return = '';
        $return .= Prepare::getHtmlLabel($element);

        /** @var \Enjoys\Forms\Element $data */
        foreach ($element->getElements() as $data) {
            $data->addClass('form-check-input');
            $data->addClass('form-check-label', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
            $data->setAttributes([
                'name' => $element->getName()
            ]);

            if (empty($data->getTitle())) {
                $data->addClass('position-static');
            }

            $data->addClass('form-check', 'checkBox');
            if ($this->rendererOptions['checkbox_inline'] === true) {
                $data->addClass('form-check-inline', 'checkBox');
            }

            if ($element->isRuleError()) {
                $data->addClass('is-invalid');
            }

            $return .= "<div{$data->getAttributes('checkBox')}>";

            $return .= Prepare::getHtmlBody($data);
            $return .= Prepare::getHtmlLabel($data);
            $return .= '</div>';
        }
        $return .= Prepare::getHtmlDescription($element)
                . Prepare::getHtmlValidation($element)
        ;

        return $return;
    }

    private function renderSelect(\Enjoys\Forms\Elements\Select $element)
    {
        $element->addClass('form-control');

        $html = '';
        if ($element->isRuleError()) {
            $html .= "<p style=\"color: red\">{$element->getRuleErrorMessage()}</p>";
        }
        $html .= "<div class=\"form-group\">
            <label for=\"{$element->getId()}\"{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$element->getTitle()}</label>
            <select{$element->getAttributes()}>";

        /** @var \Enjoys\Forms\Elements\Option $option */
        foreach ($element->getElements() as $option) {
            $html .= "<option{$option->getAttributes()}>{$option->getTitle()}</option>\n";
        }
        $html .= "</select>";

        if (!empty($element->getDescription())) {
            $html .= "\t<small>{$element->getDescription()}</small><br>\n";
        }
        return $html . "</div>\n";
    }

    private function renderDatalist(\Enjoys\Forms\Elements\Datalist $element)
    {
        $html = "\t<label for=\"{$element->getId()}\"{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$element->getTitle()}</label><br>
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

    private function renderButton(\Enjoys\Forms\Elements\Button $element)
    {
        $element->setAttributes([
            'class' => 'btn btn-primary'
        ]);

        return Prepare::getHtmlBody($element);
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
        $html .= "\t<label for=\"{$element->getId()}\"{$element->getAttributes(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$element->getTitle()}</label><br>\n";


        /** @var \Enjoys\Forms\Elements\Option $option */
        foreach ($element->getElements() as $element) {
            $html .= $this->elements([$element]);
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

        foreach ($this->elements($this->elements) as $data) {
            $html .= '<div class="form-group">';
            $html .= $data;
            $html .= '</div>';
        }
        // dump($this->prepare->getElements());
        $html .= $this->footer();
        return $html;
    }

    public function setElements(array $elements)
    {
        $this->elements = $elements;
    }
}
