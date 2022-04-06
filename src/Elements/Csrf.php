<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

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
        $session = new Session();
        $csrf_key = $csrf_key ?? '#$' . $session->getSessionId();
//        $salt = $salt ?? '$2a$07$' . md5($session->getSessionId()) . '$';
        $hash = password_hash($csrf_key, PASSWORD_DEFAULT);

        parent::__construct(Form::_TOKEN_CSRF_, $hash);

        $this->addRule(
            Rules::CALLBACK,
            'CSRF Attack detected',
            function () use ($csrf_key) {
                return password_verify($csrf_key, $this->getRequest()->getPostData(Form::_TOKEN_CSRF_, ''));
            }
        );
    }


    public function prepare()
    {
        if (!in_array($this->getForm()->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            //удаляем элемент, если был заранее создан
            //$this->getForm()->removeElement($this->getForm()->getElement(\Enjoys\Forms\Form::_TOKEN_CSRF_));
            $this->getForm()->removeElement($this);

            //возвращаем 1 что бы не добавлять элемент.
            return 1;
        }

        $this->unsetForm();
    }
}
