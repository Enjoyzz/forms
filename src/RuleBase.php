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
class RuleBase {

    private $message;
    private $params = [];

    public function __construct(?string $message = null, $params = []) {
        $this->setParams($params);
        $this->setMessage($message);
    }

    public function setParams($params) {
       
        if (is_array($params)) {
            $this->params = $params;
            return;
        }
        $this->params[] = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($key) {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    public function setMessage(?string $message): void {

        $this->message = $message;
    }

    public function getMessage(): ?string {

        return $this->message;
    }

}
