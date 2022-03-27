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

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\Length;
use Enjoys\Http\ServerRequest;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;
use PHPUnit\Framework\TestCase;
use Tests\Enjoys\Forms\Reflection;

/**
 * Description of LengthTest
 *
 * @author deadl
 */
class LengthTest extends TestCase
{

    use Reflection;


    /**
     * 
     * @dataProvider dataForTest_1_1
     */
    public function test_1_1_validate_test($value, $expect)
    {

        $text = new Text( 'foo');

        $rule = new Length(null, [
            '>' => 5
        ]);
        
        $rule->setRequestWrapper(new ServerRequestWrapper(
                        ServerRequestCreator::createFromGlobals(
                                null,
                                null,
                                null,
                                ['foo' => $value]
                        )
        ));
        
     

        //$this->$assert(\Enjoys\Forms\Validator::check([$text]));
        $this->assertEquals($expect, $rule->validate($text));
    }

    public function dataForTest_1_1()
    {
        return [
            ['test12', true],
            ['123 45', true],
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
        $rule = new Length(null, [
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
        $rule = new Length(null, [
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
        $rule = new Length(null, [
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
        $rule = new Length(null, [
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
        $rule = new Length(null, [
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
        $rule = new Length(null, [
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
        $rule = new Length(null, [
            '!==' => 5
        ]);
        $method = $this->getPrivateMethod(Length::class, 'check');
        $method->invokeArgs($rule, ['test']);
    }

}
