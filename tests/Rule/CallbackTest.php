<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Form;
use Enjoys\Forms\Validator;

class CallbackTest
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
