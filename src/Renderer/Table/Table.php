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

namespace Enjoys\Forms\Renderer\Table;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces;
use Enjoys\Forms\Renderer;
use Enjoys\Forms\Renderer\Bs4\Html\Checkbox;
use Enjoys\Forms\Renderer\Table\Html\Button;
use Enjoys\Forms\Renderer\Table\Html\Datalist;
use Enjoys\Forms\Renderer\Table\Html\Group;
use Enjoys\Forms\Renderer\Table\Html\Header;
use Enjoys\Forms\Renderer\Table\Html\Input;
use Enjoys\Forms\Renderer\Table\Html\Radio;
use Enjoys\Forms\Renderer\Table\Html\Select;

/**
 * Class Table
 * 
 * $form->setRenderer('table');

 *
 * @author Enjoys
 */
class Table extends Renderer implements Interfaces\Renderer
{

    private $elements = [];
    private $count_valid_element = 0;

    // private $prepare;

    public function __construct(Form $form, array $options = null)
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
            $html .= '<tr>';
            $html .= $data->render();
            $html .= '</tr>';
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

        return "<form{$this->form->getAttributes()}><table border='0'>";
    }

    public function footer()
    {
        return "</table></form>";
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



            if (!empty($element->getDescription())) {
                $element->setAttributes([
                    'id' => $element->getId() . 'Help',
                    'style' => 'font-size: 80%'
                        ], Form::ATTRIBUTES_DESC);
                $element->setAttributes([
                    'aria-describedby' => $element->getAttribute('id', Form::ATTRIBUTES_DESC)
                ]);
            }

            if ($element->isRuleError()) {
                $element->setAttributes([
                    'style' => 'color: red'
                        ], Form::ATTRIBUTES_VALIDATE);
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
                case 'Enjoys\Forms\Elements\Textarea':
                case 'Enjoys\Forms\Elements\File':
                case 'Enjoys\Forms\Elements\Captcha':
                    $html[] = new Input($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Image':
                case 'Enjoys\Forms\Elements\Submit':
                case 'Enjoys\Forms\Elements\Reset':
                case 'Enjoys\Forms\Elements\Button':
                    $html[] = new Button($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Header':
                    $element->setAttribute('colspan', '2');
                    $html[] = new Header($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Radio':
                    $html[] = new Radio($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Checkbox':
                    $html[] = new Checkbox($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Select':
                    $html[] = new Select($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Datalist':
                    $html[] = new Datalist($element, $this->rendererOptions);
                    break;
                case 'Enjoys\Forms\Elements\Group':
                    $html[] = new Group($element, $this);
                    break;
                default:
                    break;
            }
        }
        return $html;
    }
}
