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

use Enjoys\Forms\Captcha\CaptchaInterface;
use Enjoys\Forms\Element;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Traits;

/**
 * @author Enjoys
 */
class Captcha extends Element
{
    use Traits\Description;
    use Traits\Rules;

    /**
     *
     * @var \Enjoys\Forms\Captcha\CaptchaInterface
     */
    private CaptchaInterface $captcha;

    /**
     *
     * @param \Enjoys\Forms\Captcha\CaptchaInterface $captcha
     * @param string $message
     */
    public function __construct(CaptchaInterface $captcha, string $message = null)
    {
        parent::__construct(\uniqid('captcha'));

        $this->captcha = $captcha;
        $this->captcha->setRequestWrapper($this->getRequestWrapper());


        $this->setName($this->captcha->getName());
        $this->addRule(Rules::CAPTCHA, $this->captcha->getRuleMessage());
    }

    /**
     *
     * @return string
     */
    public function renderHtml(): string
    {
        return $this->captcha->renderHtml($this);
    }

    /**
     *
     * @return bool
     */
    public function validate(): bool
    {
        return $this->captcha->validate($this);
    }


    public function baseHtml(): string
    {
        return $this->renderHtml();
    }
}
