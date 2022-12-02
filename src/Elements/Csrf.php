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
     * @throws ExceptionRule
     * @throws \Exception
     */
    public function __construct(private Session $session)
    {
        $csrfSecret = $this->getCsrfSecret();
        $token = $this->getCsrfToken($csrfSecret);


        parent::__construct(Form::_TOKEN_CSRF_, $token);

        $this->addRule(
            Rules::CALLBACK,
            'CSRF Attack detected',
            function (string $key) {
                /** @psalm-suppress  PossiblyNullArgument, MixedArgument */
                if (password_verify($key, $this->getRequest()->getParsedBody()[Form::_TOKEN_CSRF_] ?? '')) {
                    return true;
                }
                throw new CsrfAttackDetected('CSRF Token is invalid');
            },
            $csrfSecret
        );
    }

    /**
     * @return true|void
     * @psalm-suppress  PossiblyNullReference
     */
    public function prepare()
    {
        if (!in_array($this->getForm()->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            //удаляем элемент, если был заранее создан
            //$this->getForm()->removeElement($this->getForm()->getElement(\Enjoys\Forms\Form::_TOKEN_CSRF_));
            $this->getForm()->removeElement($this);

            //возвращаем true, чтобы не добавлять элемент.
            return true;
        }
        $this->unsetForm();
    }

    /**
     * @throws \Exception
     */
    private function getCsrfSecret(): string
    {
        $secret = (string)$this->session->get('csrf_secret');

        if (empty($secret)) {
            $secret = $this->generateSecret();
        }

        return $secret;
    }


    public function getCsrfToken(string $secret): string
    {
        return password_hash($secret, PASSWORD_DEFAULT);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateSecret(): string
    {
        $secret = base64_encode(random_bytes(32));
        $this->session->set([
            'csrf_secret' => $secret
        ]);
        return $secret;
    }
}
