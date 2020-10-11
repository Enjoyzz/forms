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

namespace Enjoys\Forms\Http;

use Symfony\Component\HttpFoundation;

/**
 * Class Request
 *
 * @author Enjoys
 */
class Request extends HttpFoundation\Request implements \Enjoys\Forms\Interfaces\Request
{

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null) {
        parent::__construct(
                (empty($query)) ? $_GET : $query,
                (empty($request)) ? $_POST : $request,
                (empty($attributes)) ? [] : $attributes,
//                (empty($cookies)) ? $_COOKIE : $cookies,
//                (empty($files)) ? $_FILES : $files,
//                (empty($server)) ? $_SERVER : $server,
//                $content
        );
    }

    public function get($key = null, $default = null) {
        if ($key === null) {
            return $this->query->all();
        }
        return $this->query->get($key, $default);
    }

    public function post($key = null, $default = null) {
        if ($key === null) {
            return $this->request->all();
        }
        return $this->request->get($key, $default);
    }

}
