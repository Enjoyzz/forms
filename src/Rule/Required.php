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

namespace Enjoys\Forms\Rule;

/**
 * Description of Required
 *
 * @author deadl
 */
class Required extends \Enjoys\Forms\Rule  implements \Enjoys\Forms\Interfaces\Rule {

    public function __construct(string $message = null, array $attributes = []) {
        if (is_null($message)) {
            $message = 'Обязательно для заполнения, или выбора';
        }
        parent::__construct($message, $attributes);
    }

    public function validate(\Enjoys\Forms\Element &$element) {
    
        $request = new \Enjoys\Base\Request();
        
        if (!$this->check($request->post($element->getValidateName(), $request->get($element->getValidateName(), '')))) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }

    private function check($value) {

        if (is_array($value)) {
            return count($value) > 0;
        }
        return trim($value) != '';
    }

}
