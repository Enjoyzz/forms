<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rule\Callback;
use Enjoys\ServerRequestWrapper;
use Enjoys\Session\Session;
use HttpSoft\ServerRequest\ServerRequestCreator;

new Session();

class CsrfTest
{

    public function test_remove_hidden()
    {
        $form = new Form(
            [
                'method' => 'post'
            ]
        );
        $this->assertTrue($form->getElement(Form::_TOKEN_CSRF_) instanceof Csrf);

        $form->setMethod('get');
        $this->assertTrue(null === $form->getElement(Form::_TOKEN_CSRF_));
    }

    public function testValidateCsrfTrue()
    {
        $key = 'test';
        $request = new ServerRequestWrapper(
            ServerRequestCreator::createFromGlobals(
                null,
                null,
                null,
                null,
                [
                    Form::_TOKEN_CSRF_ => password_hash($key, PASSWORD_DEFAULT)
                ],
            )
        );


        $csrf = new Csrf($key);
        $csrf->setRequestWrapper($request);

        /** @var Callback $rule */
        $rule = $csrf->getRules()[0];
        $this->assertTrue($rule->validate($csrf));
    }

    public function testValidateCsrfFalse()
    {
        $key = 'test';
        $request = new ServerRequestWrapper(
            ServerRequestCreator::createFromGlobals(
                null,
                null,
                null,
                null,
                [
                    Form::_TOKEN_CSRF_ => 'invalid_token'
                ],
            )
        );


        $csrf = new Csrf($key);
        $csrf->setRequestWrapper($request);

        /** @var Callback $rule */
        $rule = $csrf->getRules()[0];
        $this->assertFalse($rule->validate($csrf));
    }

}
