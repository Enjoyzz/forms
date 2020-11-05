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

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionElement;
use Enjoys\Forms\DefaultsHandler;

/**
 * Description of Captcha
 *
 * @author Enjoys
 */
class Captcha extends Element
{
    use \Enjoys\Forms\Traits\Description;
    use \Enjoys\Forms\Traits\Rules;

    private $captcha;

    public function __construct(\Enjoys\Forms\Captcha\CaptchaInterface $captcha, string $message = null)
    {
        parent::__construct(\uniqid('captcha'));
        
        $this->captcha = $captcha;
        $this->captcha->setRequest($this->getRequest());
        
        
        $this->setName($this->captcha->getName());
        $this->addRule(\Enjoys\Forms\Rules::CAPTCHA, $this->captcha->getRuleMessage());
    }

    public function renderHtml()
    {
        return $this->captcha->renderHtml($this);
    }

    public function validate()
    {
        return $this->captcha->validate($this);
    }

    public function baseHtml(): ?string
    {
        return $this->renderHtml();
    }
}
