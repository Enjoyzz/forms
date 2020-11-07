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

use Enjoys\Forms\Traits\Request;

/**
 * @author Enjoys
 */
class Rules
{
    use Request;

    public const CALLBACK = Rule\Callback::class;
    public const CAPTCHA = Rule\Captcha::class;
    public const CSRF = Rule\Csrf::class;
    public const EMAIL = Rule\Email::class;
    public const EQUAL = Rule\Equal::class;
    public const LENGTH = Rule\Length::class;
    public const REGEXP = Rule\Regexp::class;
    public const REQUIRED = Rule\Required::class;
    public const UPLOAD = Rule\Upload::class;

    /**
     *
     * @var string|null
     */
    private ?string $message;

    /**
     *
     * @var array
     */
    private array $params = [];

    /**
     *
     * @param string|null $message
     * @param mixed $params
     */
    public function __construct(?string $message = null, $params = [])
    {
    
        $this->message = $this->setMessage($message);
        $this->setRequest();
        $this->setParams($params);
    }

    /**
     *
     * @param mixed $params
     * @return void
     */
    public function setParams($params): void
    {

        if (is_array($params)) {
            $this->params = $params;
            return;
        }
        $this->params[] = $params;
    }

    /**
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     *
     * @param string|int $key
     * @return mixed|null
     */
    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    /**
     * 
     * @param string|null $message
     * @return string|null
     */
    public function setMessage(?string $message = null): ?string
    {
        return $this->message = $message;
    }

    /**
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {

        return $this->message;
    }
}
