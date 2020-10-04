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

namespace Tests\Enjoys\Forms\Rule;

/**
 * Description of EqualTest
 *
 * @author deadl
 */
class EqualTest extends \PHPUnit\Framework\TestCase {

    public function tearDown(): void {
        unset($_POST);
        unset($_GET);
    }

    public function test_valid() {
        $text = new \Enjoys\Forms\Elements\Text('foo');
        $text->addRule('equal', null, ['test']);
        $_GET = [
            'foo' => 'test'
        ];
        $this->assertTrue(\Enjoys\Forms\Validator::check([$text]));
    }

    public function test_valid2() {
        $text = new \Enjoys\Forms\Elements\Text('foo');
        $text->addRule('equal', null, ['test', 'valid']);
        $_GET = [
            'foo' => 'valid'
        ];
        $this->assertTrue(\Enjoys\Forms\Validator::check([$text]));
    }

    public function test_valid3() {
        $text = new \Enjoys\Forms\Elements\Checkbox('foo');
        $text->addRule('equal', null, ['test', 2]);
        $_GET = [
            'foo' => ['2']
        ];
        $this->assertTrue(\Enjoys\Forms\Validator::check([$text]));
    }

    public function test_valid_empty() {
        $text = new \Enjoys\Forms\Elements\Checkbox('foo');
        $text->addRule('equal', null, ['test', 'valid']);
//        $_GET = [
//            'foo' => ['valid']
//        ];
        $this->assertTrue(\Enjoys\Forms\Validator::check([$text]));
    }

    public function test_invalid_array() {
        $text = new \Enjoys\Forms\Elements\Checkbox('foo');
        $text->addRule('equal', null, ['test', 'valid']);
        $_GET = [
            'foo' => ['invalid']
        ];
        $this->assertFalse(\Enjoys\Forms\Validator::check([$text]));
    }

    public function test_invalid() {
        $_GET = [
            'name' => 'fail'
        ];
        $element = new \Enjoys\Forms\Elements\Text('name');
        $element->addRule('equal', null, ['test']);
        $this->assertFalse(\Enjoys\Forms\Validator::check([$element]));
    }

}
