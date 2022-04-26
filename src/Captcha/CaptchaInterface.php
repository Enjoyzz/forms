<?php

declare(strict_types=1);

namespace Enjoys\Forms\Captcha;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;

interface CaptchaInterface
{
    public function getName(): string;

    public function getRuleMessage(): ?string;

    public function renderHtml(Element $element): string;

    /**
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool;
}
