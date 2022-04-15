<?php

namespace Tests\Enjoys\Forms\Captcha\Defaults;

use Enjoys\Forms\Captcha\Defaults\Defaults;
use Enjoys\Forms\Elements\Captcha;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;
use Webmozart\Assert\InvalidArgumentException;


class DefaultsTest extends _TestCase
{

    use Reflection;


    public function setUp(): void
    {
        parent::setUp();
        $this->session->set(
            [
                'captcha_defaults' => 'testcode'
            ]
        );
    }

    public function tearDown(): void
    {
        $this->session->delete('captcha_defaults');
        parent::tearDown();
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


    public function testGenerateCode()
    {
        srand(0);
        $captcha = new Defaults();
        $captcha->setOptions(
            [
                'size' => 5
            ]
        );

        $el = new Captcha($captcha);

        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'generateCode');
        $method->invokeArgs($captcha, [$el]);

        $this->assertEquals(5, \strlen($captcha->getCode()));
        $this->assertSame('o24ni', $captcha->getCode());
        $this->assertSame('o24ni', $this->session->get($el->getName()));
    }

    public function testGenerateCodeWithInvalidSizeOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $captcha = new Defaults();
        $captcha->setOptions(
            [
                'size' => 'xl'
            ]
        );

        $method = $this->getPrivateMethod(Defaults::class, 'generateCode');
        $method->invokeArgs($captcha, [new Captcha($captcha)]);
    }

    public function testGenerateCodeWithZeroSizeOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $captcha = new Defaults();
        $captcha->setOptions(
            [
                'size' => 0
            ]
        );

        $method = $this->getPrivateMethod(Defaults::class, 'generateCode');
        $method->invokeArgs($captcha, [new Captcha($captcha)]);
    }

    public function testCreateImgWithInvalidParams()
    {
        $this->expectError();
        $captcha = new Defaults();
        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'createImage');
        $method->invoke($captcha, 'test', 'x', 'y');
    }

    public function testCreateImgWithDefaultParams()
    {
        $captcha = new Defaults();
        $method = $this->getPrivateMethod('\Enjoys\Forms\Captcha\Defaults\Defaults', 'createImage');
        $img = $method->invoke($captcha, 'test');
        $this->assertEquals(150, \imagesx($img));
        $this->assertEquals(50, \imagesy($img));
    }

    public function testCreateImg()
    {
        srand(0);

        $captcha = new Defaults();
        $method = $this->getPrivateMethod(Defaults::class, 'createImage');
        $img = $method->invoke($captcha, 'test', '200', '100');

        $this->assertEquals(200, \imagesx($img));
        $this->assertEquals(100, \imagesy($img));

        return $img;
    }


    /**
     * @depends testCreateImg
     */
    public function testGetBase64image($img)
    {
        $captcha = new Defaults();
        $method = $this->getPrivateMethod(Defaults::class, 'getBase64Image');

        $base64img = $method->invoke($captcha, $img);
        $this->assertSame(
            '/9j/4AAQSkZJRgABAQEAYABgAAD//gA+Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBkZWZhdWx0IHF1YWxpdHkK/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAZADIAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A9eooooOMKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAIxOhbaN+f8AcP8AhUlRr/x8v/uL/M1JQMKKKKBBRRRQAUUUUAFFFFABRUc0ywRF2BPIAA6kk4A/EkU2C4853RonikUAlHxnBzg8Ejsfyp2drjtpcmooopCCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKpXV+tvP5OYFbaGJmm8sEEkccHPQ/pV2oJbbfL5iTSROVCsU2ncBnHUH1P501bqaU+W/vEkMqzwRzKCFkUMAeuCM0+iikQ99AooooEQ3YBtmzEZBkZUE5AyMkY5yOoxzxxzUVk7s8gBmaAAbGmQqwPORyASANvJ9Tyat0VSlpYq+lgoooqSQooooAarhiRg8eop1NAIduOCc5/CnUkMKKKKYgooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigD/2Q==',
            $base64img
        );

        $result = \base64_decode($base64img);
        $size = \getimagesizefromstring($result);
        $this->assertEquals(200, $size[0]);
        $this->assertEquals(100, $size[1]);
    }


    public function testRenderWithInvalidParametersWidth()
    {
        $this->expectException(InvalidArgumentException::class);
        $captcha = new Defaults();
        $captcha->setOptions([
            'width' => '100'
        ]);
        $element = new Captcha($captcha);
        $element->prepare();
        $element->renderHtml();
    }

    public function testRenderWithInvalidParametersHeight()
    {
        $this->expectException(InvalidArgumentException::class);
        $captcha = new Defaults();
        $captcha->setOptions([
            'height' => '100'
        ]);
        $element = new Captcha($captcha);
        $element->prepare();
        $element->renderHtml();
    }

    public function testRenderWithDefaultsParametersHeightAndWidth()
    {
        srand(0);
        $element = new Captcha(new Defaults());
        $element->prepare();
        $this->assertSame(
            '<img alt="captcha image" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD//gA+Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBkZWZhdWx0IHF1YWxpdHkK/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAMgCWAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A9loopksqQqGcnk4AAJJPsByakltLVj6KZFKkylkJ4OCCCCD7g8in0AmnqgorE1SS+gkleK8kE5z9jtIY1cS4Uf6zKkgbzgsGVQCvIJzW3VONkmAUUUVIBRRUcsoi25VmLHaAo74J/pSbS1Y0rklFMjkEgPylSpwVPUHr/UU+mncAoqvcX0NtIkcvm7n+7shd89eMgHng8VYptNCCiiq0VycHerkeYyb8DA+YgD19P85qW7Bcs0UUUwCiiigAqG4jdzE8e0tG+4KxwDwR15x19KmooauJq6sQ28boZXk2hpH3FVOQOAOvGenpU1FFCVgSsrGW1hqMd/dXNte2qrOVOJrVpGUBQNoYSL8udzYx1Y+talFFU5N7jCqUUm+7jZhIHaNsqUIC8rx/9f8A+sKu0hUFw+PmAIB9jj/AUkUnYWmOm942zjY2768Ef1p9FJq4hiJseRs53tu+nAH9KfRRQlYDO1G6t4LuxWW4ijImLEO4GB5bjP0zxWjRRVN3SEFQ+R+62bv+Wm/OP9rdj+lTUVNgCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigD//2Q==" /><br /><input id="captcha_defaults" name="captcha_defaults" type="text" autocomplete="off">',
            $element->renderHtml()
        );
    }

    /**
     * @throws ExceptionRule
     */
    public function testRenderHtml()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(parsedBody: [
                'captcha_defaults' => 'testcode_fail'
            ], method: 'post')
        );

        $captcha = new Defaults('code invalid');

        $element = new Captcha($captcha);
        $element->prepare();

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
            ], method: 'gEt')
        );
        $captcha = new Defaults();

        $element = new Captcha($captcha);
        $captcha->setRequest($request);
        $this->assertSame('captcha_defaults', $captcha->getName());
        $this->assertTrue($element->validate());

        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'captcha_defaults' => 'testcode_fail'
            ], method: 'get')
        );

        $captcha->setRequest($request);
        $this->assertFalse($element->validate());
    }
}
