<?php

namespace Tests\Enjoys\Forms\Elements;


use Enjoys\Forms\Captcha\Defaults\Defaults;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rule\Captcha;


class CaptchaTest
{



    public function test_init_captcha()
    {
        $form = new Form();
        $element = $form->captcha(new Defaults());
        $this->assertTrue($element instanceof \Enjoys\Forms\Elements\Captcha);
    }

    public function test_init_captcha_set_rule_message()
    {
        $form = new Form();
        $element = $form->captcha(new Defaults('test'));
        $rule = $element->getRules()[0];
        $method = $this->getPrivateMethod(Captcha::class, 'getMessage');
        $this->assertSame('test', $method->invoke($rule));
    }



}
