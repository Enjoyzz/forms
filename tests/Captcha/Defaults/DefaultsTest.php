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

use \Enjoys\Base\Session\Session;

Session::start();

/**
 * Class DefaultsTest
 *
 * @author Enjoys
 */
class DefaultsTest extends \PHPUnit\Framework\TestCase {

    public function setUp(): void {
        // Session::start();
        Session::set([
            'captcha_defaults' => 'testcode'
        ]);
    }

    public function tearDown(): void {
        Session::delete('captcha_defaults');
    }

    public function test1() {
        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults();
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

    public function test_validate() {
        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults();

        $this->assertSame('captcha_defaults', $captcha->getName());
        $this->assertFalse($captcha->getAttribute('value'));

        $captcha->setValue('testcode');
        $this->assertSame('testcode', $captcha->getAttribute('value'));
        $this->assertTrue($captcha->validate());

        $captcha->removeAttribute('value');
        $captcha->setValue('testcode_fail');
        $this->assertSame('testcode_fail', $captcha->getAttribute('value'));
        $this->assertFalse($captcha->validate());
    }
    
    

    public function test_generateCode() {

        $captcha = new \Enjoys\Forms\Captcha\Defaults\Defaults();
        $captcha->setOptions([
            'size' => 5
        ]);

        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'generateCode');
        $method->invoke($captcha);

        $this->assertEquals(5, \strlen($captcha->getCode()));
    }

    public function test_createImg() {
        $obj = new \Enjoys\Forms\Captcha\Defaults\Defaults();
        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'createImage');

        $img = $method->invoke($obj, 'test', 200, 100);
        $this->assertIsResource($img);

        $this->assertEquals(200, \imagesx($img));
        $this->assertEquals(100, \imagesy($img));

        return $img;
    }

    /**
     * @depends test_createImg
     */
    public function test_get_base64image($img) {
        $obj = new \Enjoys\Forms\Captcha\Defaults\Defaults();
        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'get_base64image');

        $result = \base64_decode($method->invoke($obj, $img));
        $size = \getimagesizefromstring($result);
        $this->assertEquals(200, $size[0]);
        $this->assertEquals(100, $size[1]);
    }

    public function test_renderHtml() {
        // $this->markTestIncomplete();
        $obj = new \Enjoys\Forms\Captcha\Defaults\Defaults('code invalid');

        $html = $obj->renderHtml();
        $this->assertEquals(6, \strlen($obj->getCode()));
        $this->assertStringContainsString('img src="data:image/jpeg;base64,', $html);
        $this->assertStringContainsString('<input id="captcha_defaults" name="captcha_defaults" type="text" autocomplete="off">', $html);

        $obj->setValue('code_fail');

        foreach ($obj->getRules() as $rule) {
            $rule->validate($obj);
        }

        $html = $obj->renderHtml();
        $this->assertStringContainsString('<p style="color: red">code invalid</p>', $html);
    }

    /**
     * getPrivateMethod
     *
     * @author	Joe Sexton <joe@webtipblog.com>
     * @param 	string $className
     * @param 	string $methodName
     * @return	\ReflectionMethod
     */
    public function getPrivateMethod($className, $methodName) {
        $reflector = new \ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

}
