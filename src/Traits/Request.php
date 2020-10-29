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

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

/**
 *
 * @author deadl
 */
trait Request
{

    /**
     *
     * @var  Request
     */
    protected ?\Enjoys\Forms\Interfaces\RequestInterface $request = null;
    protected string $requestClass;

    /**
     * @param \Enjoys\Forms\Interfaces\RequestInterface $request
     * @return \Enjoys\Forms\Interfaces\RequestInterface
     */
    public function getRequest(): \Enjoys\Forms\Interfaces\RequestInterface
    {
        if ($this->request === null) {
            $this->setRequest();
        }
        return $this->request;
    }

    /**
     * @param \Enjoys\Forms\Interfaces\RequestInterface $request
     * @return void
     */
    public function setRequest(\Enjoys\Forms\Interfaces\RequestInterface $request = null): void
    {
        $this->request = $request ?? new \Enjoys\Forms\Http\Request();
    }
}
