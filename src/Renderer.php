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

namespace Enjoys\Forms;

/**
 * Class Renderer
 *
 * @author Enjoys
 */
class Renderer
{
    use \Enjoys\Traits\Options;

    public const BOOTSTRAP4 = 'bs4';
    public const BOOTSTRAP3 = 'bs3';
    public const BOOTSTRAP2 = 'bs2';

    protected Form $form;
    private $renderer = 'bs4';

    public function __construct(Form $form, array $options = [])
    {
        $this->form = $form;
        $this->setOptions($options);
    }

    static public function create(Form $form, array $options = [])
    {
        return new static($form, $options);
    }

    public function setRenderer($renderer = null)
    {
        $this->renderer = $renderer;
        return $this;
    }

    public function display(array $options = [])
    {

        $rendererName = \ucfirst($this->renderer);
        $class = '\\Enjoys\\Forms\\Renderer\\' . $rendererName . '\\' . $rendererName;

        if (!class_exists($class)) {
            throw new Exception\ExceptionRenderer("Class <b>{$class}</b> not found");
        }
        return new $class($this->form, $this->getOptions());
    }
//    public function __toString() {
//        return 'Redefine the method <i>__toString()</i> in the class: <b>'. static::class.'</b>';
//    }
}
