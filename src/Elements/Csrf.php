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

/**
 * Включает защиту от CSRF.
 * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
 *
 * @author Enjoys
 */
class Csrf extends Hidden
{



    /**
     * @todo поменять session_id() на Session::getSessionId()
     */
    public function __construct()
    {

        $csrf_key = '#$' . session_id();
        $hash = crypt($csrf_key, '');
        parent::__construct(\Enjoys\Forms\Form::_TOKEN_CSRF_, $hash);

        $this->addRule('csrf', 'CSRF Attack detected', [
            'csrf_key' => $csrf_key
        ]);
    }

    public function prepare()
    {
        if (!in_array($this->getForm()->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $this->getForm()->removeElement(self::_TOKEN_CSRF_);
        }

        $this->unsetForm();
    }
}
