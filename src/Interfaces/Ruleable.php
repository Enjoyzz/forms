<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Enjoys\Forms\Rule\RuleInterface;

interface Ruleable
{
    public function addRule(string $ruleClass, mixed ...$params): static;
    public function setRuleError(?string $message): void;
    public function getRuleErrorMessage(): ?string;
    public function isRuleError(): bool;

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array;
}
