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

namespace Enjoys\Forms;

/**
 * Description of RuleBase
 *
 * @author deadl
 */
class Rules
{
    use Traits\Request;

    public const CALLBACK = 'callback';
    public const CAPTCHA = 'captcha';
    public const CSRF = 'csrf';
    public const EMAIL = 'email';
    public const EQUAL = 'equal';
    public const LENGTH = 'length';
    public const REGEXP = 'regexp';
    public const REQUIRED = 'required';

    /**
     *
     * @var string|null
     */
    private ?string $message;
    private array $params = [];

    /**
     * @deprecated since version 2.0.1-alpha
     * @var \Enjoys\Forms\FormDefaults
     */
    private FormDefaults $formDefaults;

    public function __construct(?string $message = null, $params = [])
    {
        $this->initRequest();

        $this->setParams($params);
        $this->setMessage($message);
    }

    /**
     * @deprecated since version 2.0.1-alpha
     * @param \Enjoys\Forms\FormDefaults $formDefaults
     */
//    public function setFormDefaults(FormDefaults $formDefaults) {
//        $this->formDefaults = $formDefaults;
//    }

    public function setParams($params)
    {

        if (is_array($params)) {
            $this->params = $params;
            return;
        }
        $this->params[] = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    public function setMessage(?string $message): void
    {

        $this->message = $message;
    }

    public function getMessage(): ?string
    {

        return $this->message;
    }
}
