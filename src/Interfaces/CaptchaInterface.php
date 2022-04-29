<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Enjoys\Forms\Element;
use Enjoys\ServerRequestWrapperInterface;

interface CaptchaInterface
{
    public function getName(): string;

    public function getRuleMessage(): ?string;

    public function renderHtml(Element $element): string;

    public function setRequest(ServerRequestWrapperInterface $request);

    public function getRequest(): ServerRequestWrapperInterface;

    /**
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool;




}
