<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Captcha\CaptchaBase;
use Enjoys\Forms\Captcha\CaptchaInterface;
use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Traits;


class Captcha extends Element implements Ruled
{
    use Traits\Description;
    use Traits\Rules;


    /**
     * @param CaptchaInterface&CaptchaBase $captcha
     * @param string|null $message
     * @throws ExceptionRule
     */
    public function __construct(private CaptchaInterface $captcha, string $message = null)
    {
        parent::__construct(\uniqid('captcha'));

        $this->captcha->setRequestWrapper($this->getRequestWrapper());


        $this->setName($this->captcha->getName());
        $this->addRule(Rules::CAPTCHA, $this->captcha->getRuleMessage());
    }

    /**
     *
     * @return string
     */
    public function renderHtml(): string
    {
        return $this->captcha->renderHtml($this);
    }

    /**
     *
     * @return bool
     */
    public function validate(): bool
    {
        return $this->captcha->validate($this);
    }


    public function baseHtml(): string
    {
        return $this->renderHtml();
    }
}
