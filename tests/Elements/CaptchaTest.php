<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Captcha\Defaults\Defaults;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\ServerRequestWrapper;
use Enjoys\Session\Session;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

new Session();

class CaptchaTest extends TestCase
{
    use Reflection;

    public function testAddedRulesCapchta()
    {
        self::markTestSkipped('need rewrite');
        $form = new Form();
        $element = $form->captcha(new Defaults());
        $this->assertNotEmpty($element->getRules());
        $this->assertContainsOnlyInstancesOf(Rules::CAPTCHA, $element->getRules());
    }

    public function testSetRequestFromForm()
    {
        self::markTestSkipped('need rewrite');
        $request = new ServerRequestWrapper(new ServerRequest(method: 'pOsT'));
        $form = new Form(request: $request);
        $element = $form->captcha(new Defaults());
        $this->assertSame($request, $element->getRequest());
        $this->assertSame($request, $element->getCaptcha()->getRequest());
    }

//    public function testCaptchaValidate()
//    {
//        $request = new ServerRequestWrapper(new ServerRequest(method: 'pOsT'));
//        $form = new Form(request: $request);
//        $element = $form->captcha(new Defaults());
//        Validator::check([$element]);
//    }
}
