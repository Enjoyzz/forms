<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Enjoys\Forms\Element;
use Psr\Http\Message\ServerRequestInterface;

interface CaptchaInterface
{
    public function getName(): string;

    public function getRuleMessage(): ?string;

    public function renderHtml(Element $element): string;

    public function setRequest(ServerRequestInterface $request): void;

    public function getRequest(): ServerRequestInterface;

    /**
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool;
}
