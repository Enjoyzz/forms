<?php

declare(strict_types=1);

namespace Enjoys\Forms\Captcha;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Options;
use HttpSoft\ServerRequest\ServerRequestCreator;

abstract class CaptchaBase implements CaptchaInterface
{
    use Options;

    protected string $name;

    protected ?string $ruleMessage = null;

    private ServerRequestWrapper $requestWrapper;

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

    public function setRequestWrapper(ServerRequestWrapper $requestWrapper = null): void
    {
        $this->requestWrapper = $requestWrapper ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
    }

    public function getRequestWrapper(): ServerRequestWrapper
    {
        return $this->requestWrapper;
    }

    abstract public function renderHtml(Element $element): string;

    /**
     * @param Ruled&Element $element
     * @return bool
     */
    abstract public function validate(Ruled $element): bool;
}
