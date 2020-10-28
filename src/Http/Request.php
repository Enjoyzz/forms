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

namespace Enjoys\Forms\Http;

use Symfony\Component\HttpFoundation;
use Enjoys\Forms\Interfaces;

/**
 * Class Request
 *
 * @author Enjoys
 */
class Request extends HttpFoundation\Request implements Interfaces\Request
{

    public function __construct(
        ?array $query = null,
        ?array $request = null,
        ?array $attributes = null,
        ?array $cookies = null,
        ?array $files = null,
        ?array $server = null,
        $content = null
    ) {
        parent::__construct(
            $query ?? $_GET,
            $request ?? $_POST,
            $attributes ?? [],
            $cookies ?? $_COOKIE,
            $files ?? $_FILES,
            $server ?? $_SERVER,
            $content
        );
    }

    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query->all();
        }
        return $this->query->get($key, $default);
    }

    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $this->request->all();
        }
        return $this->request->get($key, $default);
    }
    
    public function files($key = null, $default = null)
    {
        if ($key === null) {
            return $this->files->all();
        }
        return $this->files->get($key, $default);
    }

}
