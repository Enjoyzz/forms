<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use PHPUnit\Framework\TestCase;

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
        $this->element->addRule(Rules::CALLBACK, null, function () {
            return false;
        });
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_closure_function_true() {
        $this->element->addRule(Rules::CALLBACK, null, function () {
            return true;
        });
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_anonimous_class_fail() {
        $this->element->addRule(
            Rules::CALLBACK, null, new class {

            public $execute = 'testfunction';

            public
                    function testfunction() {
                return false;
            }
        });
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_anonimous_class_true() {
        $this->element->addRule(
            Rules::CALLBACK, null, new class {

            public function execute() {
                return true;
            }
        });
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_callback_function_fail() {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\checkFunctionReturnFalse');
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_callback_function_true() {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\checkFunctionReturnTrue');
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_callback_static_class_false() {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\ClassCheck::isFalse');
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_callback_static_class_true() {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\ClassCheck::isTrue');
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function test_callback_class_false() {
        $this->element->addRule(Rules::CALLBACK, null, [
            [
                new ClassCheck(),
                '_checkFalse'
            ]
        ]);
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function test_callback_class_true() {
        $this->element->addRule(Rules::CALLBACK, null, [
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

    public static function isFalse() {
        return false;
    }

    public static function isTrue() {
        return true;
    }

    public function _checkFalse() {
        return false;
    }

    public function _checkTrue() {
        return true;
    }

}
