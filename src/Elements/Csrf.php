<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Session\Session;

/**
 * Включает защиту от CSRF.
 * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
 *
 * @author Enjoys
 */
class Csrf extends Hidden
{

    public function __construct()
    {
        $session = new Session();
        $csrf_key = '#$' . $session->getSessionId();
        $hash = crypt($csrf_key, '$2a$07$' . md5($session->getSessionId()) . '$');

        parent::__construct(Form::_TOKEN_CSRF_, $hash);

        $this->addRule(
            Rules::CSRF,
            'CSRF Attack detected',
            [
                'csrf_key' => $csrf_key
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
            return 1;
        }

        $this->unsetForm();
    }
}
