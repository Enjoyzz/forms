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

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces;
use Enjoys\Forms\Renderer;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlButton;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlCaptcha;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlCheckbox;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlDatalist;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlFile;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlGroup;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlHeader;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlImage;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlInput;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlRadio;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlReset;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlSelect;
use Enjoys\Forms\Renderer\Bs4\Html\HtmlSubmit;

/**
 * Class Bs4
 *
 * <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
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
class Bs4 implements Interfaces\Renderer
{

    private $elements = [];
    private $count_valid_element = 0;

    // private $prepare;

    public function __construct(Form $form, array $options = null)
    {
        $this->form = $form;
        $this->rendererOptions = $options;
    //    parent::__construct($form, $options);
        // $this->prepare = new Html\Elements();
        $this->setElements($this->form->getElements());

        if ($this->rendererOptions['inline'] === true) {
            $this->form->addClass('form-inline');
        }
    }

    public function __toString()
    {
        $html = $this->header();
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
        /** @var Element $element */
        foreach ($this->elements as $key => $element) {
            if ($element instanceof Hidden) {
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

        /** @var Element $element */
        foreach ($elements as $key => $element) {
            unset($elements[$key]);

            if (!($element instanceof Element)) {
                continue;
            }

            if ($this->rendererOptions['inline'] === true) {
                $element->addClass('sr-only', Form::ATTRIBUTES_LABEL);
            }

            if (property_exists($element, 'getDescription') && !empty($element->getDescription())) {
                $element->setAttributes([
                    'id' => $element->getAttribute('id') . 'Help',
                    'class' => 'form-text text-muted'
                        ], Form::ATTRIBUTES_DESC);
                $element->setAttributes([
                    'aria-describedby' => $element->getAttribute('id', Form::ATTRIBUTES_DESC)
                ]);
            }

            if ($element->isRuleError()) {
                $element->setAttributes([
                    'class' => 'is-invalid'
                ]);
                $element->setAttributes([
                    'class' => 'invalid-feedback d-block'
                        ], Form::ATTRIBUTES_VALIDATE);
            }

            $this->count_valid_element++;
            switch (\get_class($element)) {
                case 'Enjoys\Forms\Elements\File':
                    $html[] = new HtmlFile($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Image':
                    $html[] = new HtmlImage($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Submit':
                    $html[] = new HtmlSubmit($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Reset':
                    $html[] = new HtmlReset($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Header':
                    $html[] = new HtmlHeader($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Radio':
                    $html[] = new HtmlRadio($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Checkbox':
                    $html[] = new HtmlCheckbox($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Select':
                    $html[] = new HtmlSelect($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Button':
                    $html[] = new HtmlButton($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Datalist':
                    $html[] = new HtmlDatalist($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Captcha':
                    $html[] = new HtmlCaptcha($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Group':
                    $html[] = new HtmlGroup($element, $this);
                    break;
                default:
                    $html[] = new HtmlInput($element, $this->rendererOptions);
                    break;
            }
        }
        return $html;
    }
}
