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

namespace Tests\Enjoys\Forms\Captcha\Defaults;

use \Enjoys\Session\Session;

Session::start();

/**
 * Class DefaultsTest
 *
 * @author Enjoys
 */
class DefaultsTest 
{

    use \Tests\Enjoys\Forms\Reflection;

    public function setUp(): void
    {
        // Session::start();
        Session::set([
            'captcha_defaults' => 'testcode'
        ]);
    }

    public function tearDown(): void
    {
        Session::delete('captcha_defaults');
    }

    public function test1()
    {
        $this->markTestIncomplete();
        $captcha = new \Enjoys\Forms\Elements\Captcha( 'Defaults');
        $captcha->setOption('foo', 'v_foo');
        $captcha->setOptions([
            'bar' => 'v_bar',
            'baz' => 'v_baz'
        ]);
        $this->assertArrayHasKey('foo', $captcha->getOptions());
        $this->assertArrayHasKey('bar', $captcha->getOptions());
        $this->assertEquals('v_baz', $captcha->getOption('baz'));
        $this->assertEquals('text', $captcha->getAttribute('type'));
        $this->assertEquals('off', $captcha->getAttribute('autocomplete'));
    }

    public function test_generateCode()
    {
    $this->markTestIncomplete();
        $element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();

        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults($element);
        $captcha->setOptions([
            'size' => 5
        ]);

        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'generateCode');
        $method->invoke($captcha);

        $this->assertEquals(5, \strlen($captcha->getCode()));
    }

    public function test_createImg()
    {
    $this->markTestIncomplete();
        $element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();

        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults($element);

        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'createImage');

        $img = $method->invoke($captcha, 'test', 200, 100);
        $this->assertIsResource($img);

        $this->assertEquals(200, \imagesx($img));
        $this->assertEquals(100, \imagesy($img));

        return $img;
    }

    /**
     * @depends test_createImg
     */
    public function test_get_base64image($img)
    {
        $element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();

        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults($element);


        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'getBase64Image');

        $result = \base64_decode($method->invoke($captcha, $img));
        $size = \getimagesizefromstring($result);
        $this->assertEquals(200, $size[0]);
        $this->assertEquals(100, $size[1]);
    }

    public function test_renderHtml()
    {
            $this->markTestIncomplete();
        $element = new \Enjoys\Forms\Elements\Captcha( 'Defaults');
        $element->setRequest(new \Enjoys\Forms\Http\Request([
                    'captcha_defaults' => 'testcode_fail'
        ]));
        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults($element, 'code invalid');
        $captcha->validate();

        $html = $captcha->renderHtml();
        $this->assertEquals(6, \strlen($captcha->getCode()));
        $this->assertStringContainsString('img src="data:image/jpeg;base64,', $html);
        $this->assertStringContainsString('<input id="captcha_defaults" name="captcha_defaults" type="text" autocomplete="off">', $html);
        $this->assertEquals('code invalid', $element->getRuleErrorMessage());
//        $this->assertStringContainsString('<p style="color: red">code invalid</p>', $html);
    }

    public function test_validate()
    {
            $this->markTestIncomplete();
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'Defaults');
        $captcha->setRequest(new \Enjoys\Forms\Http\Request([
                    'captcha_defaults' => 'testcode'
        ]));
        $this->assertSame('captcha_defaults', $captcha->getName());
        $this->assertTrue($captcha->validate());

        $captcha->setRequest(new \Enjoys\Forms\Http\Request([
                    'captcha_defaults' => 'testcode_fail'
        ]));
        $this->assertFalse($captcha->validate());
    }

}
