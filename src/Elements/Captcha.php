<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
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
     * @param CaptchaInterface $captcha
     * @param string|null $message
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(private CaptchaInterface $captcha, string $message = null)
    {
        $this->setRequest();
        $this->setName($this->captcha->getName());
    }

    public function prepare()
    {
        $this->captcha->setRequest($this->getRequest());
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

    public function getCaptcha(): CaptchaInterface
    {
        return $this->captcha;
    }
}
