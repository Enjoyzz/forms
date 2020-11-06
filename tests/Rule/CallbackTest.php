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

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Form;
use Enjoys\Forms\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Class CallbackTest
 *
 * @author Enjoys
 */
class CallbackTest extends TestCase
{

    private $element;

    public function setUp(): void {
        $form = new Form();
        $this->element = $form->text('foo');
    }

    public function tearDown(): void {
        unset($this->element);
    }

    public function test_closure_function_fail() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, function () {
            return false;
        });
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_closure_function_true() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, function () {
            return true;
        });
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_anonimous_class_fail() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, new class {

            public $execute = 'testfunction';

            public
                    function testfunction() {
                return false;
            }
        });
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_anonimous_class_true() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, new class {

            public function execute() {
                return true;
            }
        });
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_callback_function_fail() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\checkFunctionReturnFalse');
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_callback_function_true() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\checkFunctionReturnTrue');
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_callback_static_class_false() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\ClassCheck::isFalse');
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_callback_static_class_true() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\ClassCheck::isTrue');
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_callback_class_false() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, [
            [
                new ClassCheck(),
                '_checkFalse'
            ]
        ]);
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_callback_class_true() {
        $this->element->addRule(\Enjoys\Forms\Rules::CALLBACK, null, [
            [
                new ClassCheck(),
                '_checkTrue'
            ]
        ]);
        $this->assertTrue(Validator::check([$this->element]));
    }

}

function checkFunctionReturnFalse() {
    return false;
}

function checkFunctionReturnTrue() {
    return true;
}

class ClassCheck
{

    static public function isFalse() {
        return false;
    }

    static public function isTrue() {
        return true;
    }

    public function _checkFalse() {
        return false;
    }

    public function _checkTrue() {
        return true;
    }

}
