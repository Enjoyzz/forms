<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\CaptchaInterface;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Traits;

class Captcha extends Element implements Ruleable, Descriptionable
{
    use Traits\Description;
    use Traits\Rules;


    /**
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(private CaptchaInterface $captcha)
    {
        $this->setRequest();
        $this->setName($this->captcha->getName());
    }

    /**
     * @throws ExceptionRule
     */
    public function prepare()
    {
        $this->captcha->setRequest($this->getRequest());
        $this->addRule(Rules::CAPTCHA, $this->captcha->getRuleMessage());
    }

    public function validate(): bool
    {
        return $this->captcha->validate($this);
    }


    public function baseHtml(): string
    {
        return $this->captcha->renderHtml($this);
    }

    public function getCaptcha(): CaptchaInterface
    {
        return $this->captcha;
    }
}
