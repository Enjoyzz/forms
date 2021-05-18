<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Form;
use Enjoys\Session\Session;
use PHPUnit\Framework\TestCase;

new Session();

/**
 * Class CsrfTest
 * @package Tests\Enjoys\Forms\Elements
 */
class CsrfTest extends TestCase
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

}
