<?php

declare(strict_types=1);


namespace Enjoys\Forms\Interfaces;


interface Ruled
{
    public function addRule(string $ruleClass, ?string $message = null, mixed $params): self;
    public function setRuleError(?string $message): void;
    public function getRuleErrorMessage(): ?string;
    public function isRuleError(): bool;
    public function getRules(): array;
}