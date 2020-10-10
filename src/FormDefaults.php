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

    public function __construct(array $data, Form $form) {
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

    public function getDefaults() {
        return $this->get();
    }

    public function getValue($param) {
        return $this->get($param);
    }

    private function get($param = null) {
        if ($param === null) {
            return $this->defaults;
        }

        //return $this->getValueByIndexPath($param, $this->defaults);
        return $this->getValueByIndexPath($param, $this->defaults);
    }

    
    public function getValueByIndexPath(string $indexPath, array $data = [], int $counterId = 0) {
        
        $empty_key = 0;
        
        preg_match_all("/^([\w\d]*)|\[['\"]*(|[a-z0-9_-]+)['\"]*\]/i", $indexPath, $matches);
        $last_key = array_key_last($matches[0]);


 
        if (count($matches[0]) > 0 && !empty($matches[0][0])) {

            foreach ($matches[0] as $identify => $key) {
                
                if(!is_array($data)){
                    return false;
                }

                $key = str_replace(['[', ']', '"', '\''], [], $key);

                //если последняя и key пустой [] вернуть все
                if ($identify == $last_key && in_array($key, ['', 0], true)) {

                    if (isset($data[0]) && \count($data) > 1) {

                        break;
                    }
                }

                if ($key === '') {
                    $key = $counterId;
                }

                if (!isset($data[$key])) {
                    return false;
                }






                if ($identify == $last_key && $key !== '') {


                    if (is_array($data[$key])) {
                        return false;
                    }
                }

                    $data = $data[$key];
            }

            return $data;
        }


        return false;
    }



}
