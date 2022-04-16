<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Enjoys\Forms\Rule\RuleInterface;

interface Ruled
{
    public function addRule(string $ruleClass, ?string $message = null, mixed $params = null): self;
    public function setRuleError(?string $message): void;
    public function getRuleErrorMessage(): ?string;
    public function isRuleError(): bool;

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array;
}
