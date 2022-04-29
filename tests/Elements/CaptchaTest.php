<?php

namespace Tests\Enjoys\Forms\Elements;


use Enjoys\Forms\Elements\Captcha;
use Enjoys\Forms\Interfaces\CaptchaInterface;
use Enjoys\Forms\Rules;
use Enjoys\ServerRequestWrapperInterface;
use Enjoys\Session\Session;
use PHPUnit\Framework\TestCase;

new Session();

class CaptchaTest extends TestCase
{

    public function testSetName()
    {
        $mockCaptcha = $this->getMockBuilder(CaptchaInterface::class)->getMock();
        $mockCaptcha->expects($this->any())->method('getName')->willReturn('my-captcha-name');
        $element = new Captcha($mockCaptcha);
        $this->assertSame('my-captcha-name', $element->getName());
    }

    public function testPrepare()
    {
        $requestMock = $this->getMockBuilder(ServerRequestWrapperInterface::class)->getMock();
        $captchaMock = $this->getMockBuilder(CaptchaInterface::class)->getMock();
        $captchaMock->expects($this->any())->method('getRuleMessage')->willReturn('rule-message');

        $element = new Captcha($captchaMock);
        $element->setRequest($requestMock);

        $captchaMock->expects($this->once())->method('setRequest')
            ->with($requestMock);

        $element->prepare();
        $this->assertContainsOnlyInstancesOf(Rules::CAPTCHA, $element->getRules());
        $this->assertSame('rule-message', $element->getRules()[0]->getMessage());
    }

    public function testGetCaptchaImpl()
    {
        $mockCaptcha = $this->getMockBuilder(CaptchaInterface::class)->getMock();
        $element = new Captcha($mockCaptcha);
        $this->assertSame($mockCaptcha, $element->getCaptcha());
    }

    public function testCaptchaValidate()
    {
        $mockCaptcha = $this->getMockBuilder(CaptchaInterface::class)->getMock();
        $mockCaptcha->expects($this->any())->method('validate')->willReturn(true);
        $element = new Captcha($mockCaptcha);
        $this->assertTrue($element->validate());
    }

    public function testRenderHtml()
    {
        $mockCaptcha = $this->getMockBuilder(CaptchaInterface::class)->getMock();
        $mockCaptcha->expects($this->any())->method('renderHtml')->willReturn('<test></test>');
        $element = new Captcha($mockCaptcha);
        $this->assertSame('<test></test>', $element->renderHtml());
    }

    public function testSetRequestInConstruct()
    {
        $mockCaptcha = $this->getMockBuilder(CaptchaInterface::class)->getMock();
        $element = new Captcha($mockCaptcha);
        $this->assertInstanceOf(ServerRequestWrapperInterface::class, $element->getRequest());
    }


}
