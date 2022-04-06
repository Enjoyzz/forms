<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Exception\CsrfAttackDetected;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rule\Callback;
use Enjoys\ServerRequestWrapper;
use Enjoys\Session\Session;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

new Session();

class CsrfTest extends TestCase
{

    public function test_remove_hidden()
    {
        $form = new Form('post');
        $this->assertTrue($form->getElement(Form::_TOKEN_CSRF_) instanceof Csrf);

        $form->setMethod('get');
        $this->assertNull($form->getElement(Form::_TOKEN_CSRF_));
    }

    public function testValidateCsrfTrue()
    {
        $key = 'test';
        $request = new ServerRequestWrapper(
            new ServerRequest(parsedBody: [
                Form::_TOKEN_CSRF_ => password_hash($key, PASSWORD_DEFAULT)
            ], method: 'post')
        );

        $csrf = new Csrf($key);
        $csrf->setRequest($request);

        /** @var Callback $rule */
        $rule = $csrf->getRules()[0];
        $this->assertTrue($rule->validate($csrf));
    }

    public function testValidateCsrfFalse()
    {
        $this->expectException(CsrfAttackDetected::class);
        $key = 'test';
        $request = new ServerRequestWrapper(
            new ServerRequest(parsedBody: [
                Form::_TOKEN_CSRF_ => 'invalid_token'
            ], method: 'post')
        );

        $csrf = new Csrf($key);
        $csrf->setRequest($request);

        $csrf->getRules()[0]->validate($csrf);

    }

}
