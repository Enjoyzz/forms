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
namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Forms;
/**
 * Description of HiddenTest
 *
 * @author deadl
 */
class HiddenTest extends \PHPUnit\Framework\TestCase{
    
    /**
     * @dataProvider dataForConstruct
     */
    public function test_full_construct($name, $value, $expectName, $expectValue) {
        $obj = new \Enjoys\Forms\Elements\Hidden(new \Enjoys\Forms\FormDefaults([]), $name, $value);
        $this->assertSame($expectName, $obj->getAttribute('name'));
        $this->assertSame($expectValue, $obj->getAttribute('value'));
        $this->assertSame(false, $obj->getAttribute('id'));
    }
    
    public function dataForConstruct() {
        return [
            ['name', 'value', 'name', 'value'],
            ['name', null, 'name', false]
        ];
    }
}