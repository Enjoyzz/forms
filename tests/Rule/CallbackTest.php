<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Tests\Enjoys\Forms\_TestCase;

class CallbackTest extends _TestCase
{
    private $element;

    public function setUp(): void
    {
        $form = new Form();
        $this->element = $form->text('foo');
    }

    public function tearDown(): void
    {
        unset($this->element);
    }

    public function testClosureFunctionFail()
    {
        $this->element->addRule(Rules::CALLBACK, null, function () {
            return false;
        });
        $this->assertFalse(Validator::check([$this->element]));
        $this->assertSame('Ошибка', $this->element->getRuleErrorMessage());
    }

    public function testClosureFunctionTrue()
    {
        $this->element->addRule(Rules::CALLBACK, null, function () {
            return true;
        });
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function testClosureFunctionWithParamsTrue()
    {
        $param1 = 'test';
        $param2 = 'test2';
        $this->element->addRule(Rules::CALLBACK, null, [
            function ($p1, $p2) {
                if ($p1 === 'test' && $p2 === 'test2') {
                    return true;
                }
                return false;
            },
            $param1,
            $param2
        ]);
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function testCallbackFunctionFail()
    {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\checkFunctionReturnFalse');
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function testCallbackFunctionTrue()
    {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\checkFunctionReturnTrue');
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function testCallbackStaticClassFalse()
    {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\ClassCheck::isFalse');
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function testCallbackStaticClassTrue()
    {
        $this->element->addRule(Rules::CALLBACK, null, 'Tests\Enjoys\Forms\Rule\ClassCheck::isTrue');
        $this->assertTrue(Validator::check([$this->element]));
    }

    public function testCallbackClassFalse()
    {
        $this->element->addRule(Rules::CALLBACK, null, [
            [
                new ClassCheck(),
                '_checkFalse'
            ]
        ]);
        $this->assertFalse(Validator::check([$this->element]));
    }

    public function testCallbackClassTrue()
    {
        $this->element->addRule(Rules::CALLBACK, null, [
            [
                new ClassCheck(),
                '_checkTrue'
            ]
        ]);
        $this->assertTrue(Validator::check([$this->element]));
    }
}

function checkFunctionReturnFalse()
{
    return false;
}

function checkFunctionReturnTrue()
{
    return true;
}


class ClassCheck
{
    public static function isFalse()
    {
        return false;
    }

    public static function isTrue()
    {
        return true;
    }

    public function _checkFalse()
    {
        return false;
    }

    public function _checkTrue()
    {
        return true;
    }
}
