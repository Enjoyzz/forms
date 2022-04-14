<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Exception\CsrfAttackDetected;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Session\Session;

/**
 * Включает защиту от CSRF.
 * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
 */
class Csrf extends Hidden
{

    /**
     * @psalm-suppress PossiblyNullArgument
     * @throws ExceptionRule
     */
    public function __construct(string $csrf_key = null)
    {

        $csrf_key = $csrf_key ?? $this->makeCsrfKey();


        parent::__construct(Form::_TOKEN_CSRF_, password_hash($csrf_key, PASSWORD_DEFAULT));

        $this->addRule(
            Rules::CALLBACK,
            'CSRF Attack detected',
            [
                function (string $key) {
                    if (password_verify($key, $this->getRequest()->getPostData(Form::_TOKEN_CSRF_, ''))) {
                        return true;
                    }
                    throw new CsrfAttackDetected('CSRF Token is invalid');
                },
                $csrf_key
            ]
        );
    }


    public function prepare()
    {
        if (!in_array($this->getForm()->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            //удаляем элемент, если был заранее создан
            //$this->getForm()->removeElement($this->getForm()->getElement(\Enjoys\Forms\Form::_TOKEN_CSRF_));
            $this->getForm()->removeElement($this);

            //возвращаем 1 что бы не добавлять элемент.
            return true;
        }

        $this->unsetForm();
    }

    private function makeCsrfKey()
    {
        $session = new Session();
        return '#$' . $session->getSessionId();
    }
}
