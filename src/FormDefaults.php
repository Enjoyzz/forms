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

namespace Enjoys\Forms;

use Enjoys\Base\Request;
use Enjoys\Helpers\Arrays;

/**
 * Class Defaults
 *
 * @author Enjoys
 */
class FormDefaults
{

    private $defaults = [];

    public function __construct(array $data, Form $form)
    {
        $this->defaults = $data;
        $request = new Http\Request();

        if ($form->isSubmited()) {
            $this->defaults = [];
            $method = \strtolower($form->getMethod());

            //записываем флаг/значение каким методом отправлена форма
            $this->defaults[Form::_FLAG_FORMMETHOD_] = $method;


            foreach ($request->$method() as $key => $items) {
                $this->defaults[$key] = $items;
            }
        }
    }

    public function getDefaults()
    {
        return $this->get();
    }

    public function getValue($param)
    {
        return $this->get($param);
    }

    private function get($param = null)
    {
        if ($param === null) {
            return $this->defaults;
        }
        return Http\Request::getValueByIndexPath($param, $this->defaults);
    }

}
