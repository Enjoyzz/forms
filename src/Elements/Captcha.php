<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
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

namespace Enjoys\Forms\Elements;

/**
 * Description of Captcha
 *
 * @author deadl
 */
class Captcha extends \Enjoys\Forms\Element
{

    private $captcha;

    public function __construct(\Enjoys\Forms\FormDefaults $formDefaults, string $captcha = null, string $message = null)
    {
        if (is_null($captcha)) {
            $captcha = 'Defaults';
        }
        parent::__construct($formDefaults, \uniqid('captcha'));
        $this->captcha = $this->getCaptcha($captcha, $message);
    }

    public function setOptions(array $options)
    {
        $this->captcha->setOptions($options);
        return $this;
    }

    public function setOption($name, $value)
    {
        $this->captcha->setOption($name, $value);
        return $this;
    }
    
    public function getOptions()
    {
        return $this->captcha->getOptions();
    }
    
    public function getOption($param, $default = null)
    {
        return $this->captcha->getOption($param, $default);
    }

    public function renderHtml()
    {
        return $this->captcha->renderHtml();
    }

    public function validate()
    {
        return $this->captcha->validate();
    }

    private function getCaptcha($captcha, string $message = null)
    {

        $class = "\Enjoys\Forms\Captcha\\" . $captcha . "\\" . $captcha;

        if (!class_exists($class)) {
            throw new \Enjoys\Forms\Exception\ExceptionElement("Class <b>{$class}</b> not found");
        }

        /** @var \Enjoys\Forms\Interfaces\Captcha $element */
        return new $class($this, $message);
    }
}
