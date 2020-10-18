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

namespace Enjoys\Forms\Renderer\Bs4;

use Enjoys\Forms\Interfaces;
use Enjoys\Forms\Renderer\RenderBase;

/**
 * Class Bs4
 * 
 * $form->setRenderer('bs4');
 * $form->display([
 *      'inline' => true|false,
 *      'custom-radio' => true|false,
 *      'custom-checkbox' => true|false,
 *      'checkbox_inline' => true|false,
 * ]);
 *
 * @author Enjoys
 */
class Bs4 extends \Enjoys\Forms\Renderer implements Interfaces\Renderer
{

    private $elements = [];
    private $count_valid_element = 0;

    // private $prepare;

    public function __construct(\Enjoys\Forms\Form $form, array $options = null)
    {
        parent::__construct($form, $options);
        // $this->prepare = new Html\Elements();
        $this->setElements($this->form->getElements());

        if ($this->rendererOptions['inline'] === true) {
            $this->form->addClass('form-inline');
        }
    }

    public function __toString()
    {
        $html = '';
        $html .= $this->header();
        $html .= $this->hidden();

        foreach ($this->elements($this->elements) as $data) {
            $html .= '<div class="form-group">';
            $html .= $data->render();
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

    public function header()
    {

        return "<form{$this->form->getAttributes()}>\n";
    }

    public function footer()
    {
        return "</form>";
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
        $html = [];

        /** @var \Enjoys\Forms\Element $element */
        foreach ($elements as $key => $element) {
            unset($elements[$key]);

            if (!($element instanceof \Enjoys\Forms\Element)) {
                continue;
            }

            if ($this->rendererOptions['inline'] === true) {
                $element->addClass('sr-only', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
            }

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
                    'class' => 'invalid-feedback d-block'
                        ], \Enjoys\Forms\Form::ATTRIBUTES_VALIDATE);
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
                    $html[] = new Html\Input($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Textarea':
                    $html[] = new Html\Textarea($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\File':
                    $html[] = new Html\File($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Image':
                    $html[] = new Html\Image($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Submit':
                    $html[] = new Html\Submit($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Reset':
                    $html[] = new Html\Reset($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Header':
                    $html[] = new Html\Header($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Radio':
                    $html[] = new Html\Radio($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Checkbox':
                    $html[] = new Html\Checkbox($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Select':
                    $html[] = new Html\Select($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Button':
                    $html[] = new Html\Button($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Datalist':
                    $html[] = new Html\Datalist($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Captcha':
                    $html[] = new Html\Captcha($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Group':
                    $html[] = new Html\Group($element, $this);
                    break;
                default:
                    break;
            }
        }
        return $html;
    }
}
