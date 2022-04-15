<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Captcha;

use Enjoys\Forms\Captcha\CaptchaBase;
use Tests\Enjoys\Forms\_TestCase;

class CaptchaBaseTest extends _TestCase
{
    public function testGetName()
    {
        $captcha = $this->getMockForAbstractClass(CaptchaBase::class);
        $name = uniqid('captcha');
        $captcha->setName($name);
        $this->assertSame($name, $captcha->getName());
    }

    public function testGetRuleMessage()
    {
        $captcha = $this->getMockForAbstractClass(CaptchaBase::class);
        $errorMessage = uniqid('error');
        $captcha->setRuleMessage($errorMessage);
        $this->assertSame($errorMessage, $captcha->getRuleMessage());
    }
}
