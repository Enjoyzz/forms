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

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Description of EqualTest
 *
 * @author deadl
 */
class EqualTest extends TestCase
{

    public function tearDown(): void {
        $_GET = [];
        $_POST = [];
    }

    public function test_validate() {
        $text = new Text(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, ['test']);
        $_GET = [
            'foo' => 'test'
        ];
        $this->assertTrue(Validator::check([$text]));
    }

    public function test_valid2() {
         $text = new Text(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, ['test', 'valid']);
        $_GET = [
            'foo' => 'valid'
        ];
        $this->assertTrue(Validator::check([$text]));
    }

    public function test_valid3() {
        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, ['test', 2]);
        $_GET = [
            'foo' => ['2']
        ];
        $this->assertTrue(Validator::check([$text]));
    }

    public function test_valid3_1() {
        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, ['test', 2]);
        $_GET = [
            'foo' => ['test']
        ];
        $this->assertTrue(Validator::check([$text]));
    }

    public function test_valid_4_1() {
        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'bar[][][]');
        $text->addRule('equal', null, ['test', 2]);
        $_GET = [
            'bar' => ['test']
        ];
        $this->assertTrue(Validator::check([$text]));
    }

    public function test_invalid_4_1() {
        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'bar[][][]');
        $text->addRule('equal', null, ['test', 2]);
        $_GET = [
            'bar' => [
                [
                    [
                        'failtest'
                    ]
                ]
            ]
        ];
        $this->assertFalse(Validator::check([$text]));
    }

    public function test_valid_empty() {
        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, ['test', 'valid']);
//        $_GET = [
//            'foo' => ['valid']
//        ];
        $this->assertTrue(Validator::check([$text]));
    }

    public function test_invalid_array() {

        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, ['test', 'valid']);
        $_GET = [
            'foo' => ['invalid']
        ];
        $this->assertFalse(Validator::check([$text]));
    }

    public function test_invalid_array2() {

        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, [1]);
        $_GET = [
            'foo' => [0]
        ];
        $this->assertFalse(Validator::check([$text]));
    }

    public function test_invalid_array3() {

        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, [1]);
        $_GET = [
            'foo' => [
                1, 2
            ]
        ];
        $this->assertFalse(Validator::check([$text]));
    }
    
    public function test_valid_array3() {

        $text = new Checkbox(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $text->addRule('equal', null, [1,2,3]);
        $_GET = [
            'foo' => [
                1, 3
            ]
        ];
        $this->assertTrue(Validator::check([$text]));
    }    

    public function test_invalid() {
        $_GET = [
            'name' => 'fail'
        ];
        $element = new Text(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'name');
        $element->addRule('equal', null, ['test']);
        $this->assertFalse(Validator::check([$element]));
    }

}
