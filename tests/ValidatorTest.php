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
class ValidatorTest extends \PHPUnit\Framework\TestCase
{

    //  private $request;
    public function setUp(): void
    {
        // $this->request
    }

    public function tearDown(): void
    {
        $_GET = [];
        $_POST = [];
    }

    public function test_validate_true()
    {
        $form = new \Enjoys\Forms\Form();
        $_GET = [
            'foo' => 'v_foo',
            'bar' => 'v_bar'
        ];
        $elements = [
            $form->text('foo')->addRule('required'),
            $form->text('bar')->addRule('required'),
        ];

        $this->assertTrue(\Enjoys\Forms\Validator::check($elements));
    }

    public function test_validate_false()
    {
        $form = new \Enjoys\Forms\Form();
        $_GET = [
            'foo' => 'v_foo',
        ];
        $elements = [
            $form->text('foo')->addRule('required'),
            $form->text('bar')->addRule('required'),
        ];

        $this->assertFalse(\Enjoys\Forms\Validator::check($elements));
    }

    public function test_validate_without_rules()
    {
        $form = new \Enjoys\Forms\Form();
        $elements = [
            $form->text('foo'),
            $form->text('bar'),
        ];

        $this->assertTrue(\Enjoys\Forms\Validator::check($elements));
    }

    public function test_validate_without_elements()
    {
        $this->assertTrue(\Enjoys\Forms\Validator::check([]));
    }

}
