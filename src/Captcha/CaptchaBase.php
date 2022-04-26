<?php

declare(strict_types=1);

namespace Enjoys\Forms\Captcha;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Request;
use Enjoys\Traits\Options;

abstract class CaptchaBase implements CaptchaInterface
{
    use Options;
    use Request;

    protected string $name;

    protected ?string $ruleMessage = null;


    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRuleMessage(): ?string
    {
        return $this->ruleMessage;
    }

    public function setRuleMessage(?string $message = null): void
    {
        $this->ruleMessage = $message;
    }

    abstract public function renderHtml(Element $element): string;

    /**
     * @param Ruleable&Element $element
     * @return bool
     */
    abstract public function validate(Ruleable $element): bool;
}
