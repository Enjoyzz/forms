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

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Captcha\Defaults\Defaults;
use Enjoys\Forms\Rule\Captcha;
use PHPUnit\Framework\TestCase;

/**
 * Class CaptchaTest
 *
 * @author Enjoys
 */
class CaptchaTest extends TestCase
{

    public function test_validate_success()
    {
        $mock_element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();
        $mock_element->expects($this->any())->method('validate')->will($this->returnValue(true));


        $rule = new Captcha();
        $this->assertTrue($rule->validate($mock_element));
    }

    public function test_validate_fail()
    {
        $mock_element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();
        $mock_element->expects($this->any())->method('validate')->will($this->returnValue(false));


        $rule = new Captcha();
        $this->assertFalse($rule->validate($mock_element));
    }

}
