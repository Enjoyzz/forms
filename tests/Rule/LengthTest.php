<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\Length;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class LengthTest extends TestCase
{
    use Reflection;


    /**
     *
     * @dataProvider dataForTest_1_1
     */
    public function test_1_1_validate_test($value, $expect)
    {

        $text = new Text('foo');

        $rule = new Length([
            '>' => 5
        ]);

        $rule->setRequest(new ServerRequestWrapper(
            new ServerRequest(queryParams:  ['foo' => $value], parsedBody: [], method: 'gEt')
        ));
        //$this->$assert(\Enjoys\Forms\Validator::check([$text]));
        $this->assertEquals($expect, $rule->validate($text));
        if (!$expect) {
            $this->assertSame('Ошибка ввода', $text->getRuleErrorMessage());
        }
    }

    public function dataForTest_1_1()
    {
        return [
            ['test12', true],
            ['123 45', true],
            [123456, true],
            ['привет', true],
            ['кот23', false],
            ['      ', false],
            ['', true],
            [[], true],
        ];
    }

    /**
     *
     * @dataProvider dataForTest_1_2
     */
    public function test_1_2($value, $expect)
    {
        $rule = new Length([
            '<' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $this->assertEquals($expect, $method->invokeArgs($rule, [$value]));
    }

    public function dataForTest_1_2()
    {
        return [
            ['test1', false],
            ['123 45', false],
            ['привет', false],
            ['кот23', false],
            ['      ', true],
            ['1234', true],
            ['', true],
            [[], true],
        ];
    }

    /**
     *
     * @dataProvider dataForTest_2_1
     */
    public function test_2_1($value, $expect)
    {
        $rule = new Length([
            '>=' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $this->assertEquals($expect, $method->invokeArgs($rule, [$value]));
    }

    public function dataForTest_2_1()
    {
        return [
            ['test12', true],
            ['123 45', true],
            ['привет', true],
            ['кот23', true],
            ['', true],
            [[1, 2], true],
            ['      ', false],
            ['1234', false],
        ];
    }

    /**
     *
     * @dataProvider dataForTest_2_2
     */
    public function test_2_2($value, $expect)
    {
        $rule = new Length([
            '<=' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $this->assertEquals($expect, $method->invokeArgs($rule, [$value]));
    }

    public function dataForTest_2_2()
    {
        return [
            ['test12', false],
            ['123 45', false],
            ['привет', false],
            ['кот23', true],
            ['', true],
            [[], true],
            ['      ', true],
            ['1234', true],
        ];
    }

    /**
     *
     * @dataProvider dataForTest_3_1
     */
    public function test_3_1($value, $expect)
    {
        $rule = new Length([
            '==' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $this->assertEquals($expect, $method->invokeArgs($rule, [$value]));
    }

    public function dataForTest_3_1()
    {
        return [
            ['test12', false],
            ['123 45', false],
            ['привет', false],
            ['кот23', true],
            ['', true],
            [[], true],
            ['      ', false],
            ['1234', false],
        ];
    }

    /**
     *
     * @dataProvider dataForTest_3_2
     */
    public function test_3_2($value, $expect)
    {
        $rule = new Length([
            '!=' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $this->assertEquals($expect, $method->invokeArgs($rule, [$value]));
    }

    public function dataForTest_3_2()
    {
        return [
            ['test12', true],
            ['123 45', true],
            ['привет', true],
            ['кот23', false],
            ['', true],
            [[], true],
            ['      ', true],
            ['1234', true],
        ];
    }

    /**
     *
     * @dataProvider dataForTest_3_3
     */
    public function test_3_3($value, $expect)
    {
        $rule = new Length([
            '!=' => 5
        ]);

        $method = $this->getPrivateMethod(Length::class, 'check');
        $this->assertEquals($expect, $method->invokeArgs($rule, [$value]));
    }

    public function dataForTest_3_3()
    {
        return [
            ['test12', true],
            ['123 45', true],
            ['привет', true],
            ['кот23', false],
            ['', true],
            [[], true],
            ['      ', true],
            ['1234', true],
        ];
    }

    public function test_invalid_operator()
    {
        $this->expectException(ExceptionRule::class);
        $rule = new Length([
            '!==' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $method->invokeArgs($rule, ['test']);
    }

    /**
     * @dataProvider dataForTestValidate
     */
    public function testValidateInForm($message, $rule, $request, $expect)
    {
        self::markTestIncomplete();
        $text = new Text('foo');

        $text->setRequest(
            new ServerRequestWrapper(
                new ServerRequest(queryParams: $request, parsedBody: [], method: 'gEt')
            )
        );
        $text->addRule(Rules::LENGTH, $rule, $message);
        $this->assertEquals($expect, Validator::check([$text]));
        if (!$expect) {
            $this->assertSame($message === null ? 'Ошибка' : $message, $text->getRuleErrorMessage());
        }
    }

    public function dataForTestValidate()
    {
        return [
            [null, [] ,['foo' => 'test@test.com'], true],
            [null, [], ['foo' => 'test@localhost'], false],
            [null, [], ['foo' => '    '], true],
        ];
    }
}
