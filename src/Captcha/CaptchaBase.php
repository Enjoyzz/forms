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

namespace Enjoys\Forms\Captcha;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Options;
use HttpSoft\ServerRequest\ServerRequestCreator;

/**
 *
 * @author Enjoys
 */
abstract class CaptchaBase implements CaptchaInterface
{
    use Options;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @var string
     */
    protected string $name;

    /**
     *
     * @var string|null
     */
    protected ?string $ruleMessage = null;
    private ServerRequestWrapper $requestWrapper;

    /**
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return string|null
     */
    public function getRuleMessage(): ?string
    {
        return $this->ruleMessage;
    }

    /**
     *
     * @param string|null $message
     * @return void
     */
    public function setRuleMessage(?string $message = null): void
    {
        $this->ruleMessage = $message;
    }


    public function setRequestWrapper(ServerRequestWrapper $requestWrapper = null)
    {
        $this->requestWrapper = $requestWrapper ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
    }

    public function getRequestWrapper(): ServerRequestWrapper
    {
        return $this->requestWrapper;
    }


    abstract public function renderHtml(Element $element): string;

    abstract public function validate(Ruled $element): bool;
}
