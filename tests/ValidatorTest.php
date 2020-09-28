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

namespace Tests\Enjoys\Forms;

/**
 * Class ValidatorTest
 *
 * @author Enjoys
 */
class ValidatorTest extends \PHPUnit\Framework\TestCase {

    public function tearDown(): void {
         unset($_POST);
         unset($_GET);
    }

    public function test_validate() {


        $_POST = [
            'foo' => 'foo', 'food' => 'food'
        ];
        $element1 = new \Enjoys\Forms\Elements\Text('foo');
        $element1->addRule('required');
        $element2 = new \Enjoys\Forms\Elements\Text('food');
        $element2->addRule('required');
        $this->assertTrue(\Enjoys\Forms\Validator::check([$element1, $element2]));
    }

    public function test_validate2() {

        $_GET = [
            'food' => 'food'
        ];

        $element = new \Enjoys\Forms\Elements\Text('foo');
        $element->addRule('required');

        $element2 = new \Enjoys\Forms\Elements\Text('food');
        $element2->addRule('required');

        $this->assertFalse(\Enjoys\Forms\Validator::check([$element, $element2]));
    }

    public function test_validate3() {
        $element = new \Enjoys\Forms\Elements\Text('foo');
        $this->assertTrue(\Enjoys\Forms\Validator::check([$element]));
    }

    public function test_validate4() {
        $this->assertTrue(\Enjoys\Forms\Validator::check([]));
    }

}
