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

use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\Http\ServerRequest;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 *
 * @author Enjoys
 */
class ValidatorTest extends TestCase
{

//
//    public function test_validate_true()
//    {
//        $form = new \Enjoys\Forms\Form([], new \Enjoys\Forms\Http\Request([
//                    'foo' => 'v_foo',
//                    'bar' => 'v_bar'
//        ]));
//
//        $elements = [
//            $form->text('foo')->addRule('required'),
//            $form->text('bar')->addRule('required'),
//        ];
//
//        $this->assertTrue(\Enjoys\Forms\Validator::check($elements));
//    }
//
//    public function test_validate_false()
//    {
//        $form = new \Enjoys\Forms\Form([], new \Enjoys\Forms\Http\Request([
//                    'foo' => 'v_foo',
//        ]));
//
//        $elements = [
//            $form->text('foo')->addRule('required'),
//            $form->text('bar')->addRule('required'),
//        ];
//
//        $this->assertFalse(\Enjoys\Forms\Validator::check($elements));
//    }
//
//    public function test_validate_without_rules()
//    {
//        $form = new \Enjoys\Forms\Form();
//        $elements = [
//            $form->text('foo'),
//            $form->text('bar'),
//        ];
//
//        $this->assertTrue(\Enjoys\Forms\Validator::check($elements));
//    }
//
//    public function test_validate_without_elements()
//    {
//        $this->assertTrue(\Enjoys\Forms\Validator::check([]));
//    }

    public function test_validate_groups_true()
    {


        $request = new ServerRequestWrapper(
                ServerRequestCreator::createFromGlobals(
                        null,
                        null,
                        null,
                        [
                            'foo' => 'v_foo'
                        ]
                )
        );
        $form = new Form([], $request);
        $group = $form->group();
        $group->textarea('bararea');
        $group->text('foo')->addRule(Rules::REQUIRED);
        $this->assertEquals(true, Validator::check($form->getElements()));
    }

    public function test_validate_groups_false()
    {

        $request = new ServerRequestWrapper(
                ServerRequestCreator::createFromGlobals(
                        null,
                        null,
                        null,
                        [
                            'food' => 'v_foo'
                        ]
                )
        );

        $form = new Form([], $request);
        $group = $form->group();
        $group->textarea('bararea');
        $group->reset('reset');
        $group->text('foo')->addRule(Rules::REQUIRED);
        $this->assertEquals(false, Validator::check($form->getElements()));
    }
}
