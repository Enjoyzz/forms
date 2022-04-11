<?php

namespace Tests\Enjoys\Forms\Captcha\Defaults;

use Enjoys\Forms\Captcha\Defaults\Defaults;
use Enjoys\Forms\Elements\Captcha;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\ServerRequestWrapper;
use Enjoys\Session\Session;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;


new Session();


class DefaultsTest extends TestCase
{

    use Reflection;

    private Session $session;

    public function setUp(): void
    {
        $this->session = new Session();

        $this->session->set(
            [
                'captcha_defaults' => 'testcode'
            ]
        );
    }

    public function tearDown(): void
    {
        $this->session->delete('captcha_defaults');
    }

    public function test1()
    {
        $captcha = new Defaults();
        $captcha->setOption('foo', 'v_foo');
        $captcha->setOptions(
            [
                'bar' => 'v_bar',
                'baz' => 'v_baz'
            ]
        );

        $captcha_element = new Captcha($captcha);
        $captcha_element->renderHtml();

        $this->assertArrayHasKey('foo', $captcha->getOptions());
        $this->assertArrayHasKey('bar', $captcha->getOptions());
        $this->assertEquals('v_baz', $captcha->getOption('baz'));
        $this->assertEquals('text', $captcha_element->getAttr('type')->getValueString());
        $this->assertEquals('off', $captcha_element->getAttr('autocomplete')->getValueString());
    }

    public function test_generateCode()
    {
        $element = $this->getMockBuilder(Captcha::class)
            ->disableOriginalConstructor()
            ->getMock();

        $captcha = new Defaults();
        $captcha->setOptions(
            [
                'size' => 5
            ]
        );


        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'generateCode');
        $method->invokeArgs($captcha, [$element]);

        $this->assertEquals(5, \strlen($captcha->getCode()));
    }

    public function test_createImg()
    {
//        $element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
//                ->disableOriginalConstructor()
//                ->getMock();

        $captcha = new Defaults();

        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'createImage');

        $img = $method->invoke($captcha, 'test', 200, 100);
        //$this->assertIsResource($img);

        $this->assertEquals(200, \imagesx($img));
        $this->assertEquals(100, \imagesy($img));

        return $img;
    }

    /**
     * @depends test_createImg
     */
    public function test_get_base64image($img)
    {
//        $element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
//                ->disableOriginalConstructor()
//                ->getMock();

        $captcha = new Defaults();


        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'getBase64Image');

        $result = \base64_decode($method->invoke($captcha, $img));
        $size = \getimagesizefromstring($result);
        $this->assertEquals(200, $size[0]);
        $this->assertEquals(100, $size[1]);
    }

    /**
     * @throws ExceptionRule
     */
    public function test_renderHtml()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(parsedBody: [
                'captcha_defaults' => 'testcode_fail'
            ], method: 'post')
        );

        $captcha = new Defaults('code invalid');

        $element = new Captcha($captcha);
        $element->setRequest($request);

        $element->validate();

        $html = $element->renderHtml();
        $this->assertEquals(6, \strlen($captcha->getCode()));
        $this->assertStringContainsString('img alt="captcha image" src="data:image/jpeg;base64,', $html);
        $this->assertStringContainsString(
            '<input id="captcha_defaults" name="captcha_defaults" type="text" autocomplete="off">',
            $html
        );
        $this->assertEquals('code invalid', $element->getRuleErrorMessage());
//        $this->assertStringContainsString('<p style="color: red">code invalid</p>', $html);
    }

    public function test_validate()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'captcha_defaults' => 'testcode'
            ], method: 'get')
        );
        $captcha = new Defaults();

        $element = new Captcha($captcha);
        $captcha->setRequestWrapper($request);
        $this->assertSame('captcha_defaults', $captcha->getName());
        $this->assertTrue($element->validate());

        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'captcha_defaults' => 'testcode_fail'
            ], method: 'get')
        );

        $captcha->setRequestWrapper($request);
        $this->assertFalse($element->validate());
    }
}
