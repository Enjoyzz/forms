<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Exception\CsrfAttackDetected;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rule\Callback;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;
use Tests\Enjoys\Forms\Reflection;

class CsrfTest extends _TestCase
{
    use Reflection;

    public function testGenerateSecret()
    {
        $csrf = new Csrf($this->session);
        $method = $this->getPrivateMethod(Csrf::class, 'generateSecret');
        $result = $method->invoke($csrf);
        $this->assertTrue($this->session->has('csrf_secret'));
        $this->assertSame(32, strlen(base64_decode($result, true)));
    }
    public function testRemoveHidden()
    {
        $form = new Form('post');
        $this->assertTrue($form->getElement(Form::_TOKEN_CSRF_) instanceof Csrf);

        $form->setMethod('get');
        $this->assertNull($form->getElement(Form::_TOKEN_CSRF_));
    }

    public function testUnsetFormAfterPrepareWhenValid()
    {
        $form = new Form('post', '/action');
        $csrf = new Csrf($this->session);
        $csrf->setForm($form);
        $this->assertEquals($form, $csrf->getForm());
        $csrf->prepare();
        $this->assertNotEquals($form, $csrf->getForm());
    }

    public function testValidateCsrfTrue()
    {
        $key = 'test';

        $this->session->set([
            'csrf_secret' => $key
        ]);

        $csrf = new Csrf($this->session);



        $request = new ServerRequest(parsedBody: [
            Form::_TOKEN_CSRF_ => $csrf->getCsrfToken($key)
        ], method: 'post');


        $csrf->setRequest($request);

        /** @var Callback $rule */
        $rule = $csrf->getRules()[0];
        $this->assertTrue($rule->validate($csrf));
    }

    public function testValidateCsrfFalse()
    {
        $this->expectException(CsrfAttackDetected::class);

        $request =  new ServerRequest(parsedBody: [
            Form::_TOKEN_CSRF_ => 'invalid_token'
        ], method: 'post');

        $csrf = new Csrf($this->session);
        $csrf->setRequest($request);

        $csrf->getRules()[0]->validate($csrf);
    }

    public function testInvalidSessionSecret()
    {
        $this->expectError();

        $this->session->set([
            'csrf_secret' => []
        ]);

        new Csrf($this->session);
    }
}
