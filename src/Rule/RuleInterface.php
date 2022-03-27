<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Interfaces\Ruled;


interface RuleInterface
{

    public function __construct(string $message = null);

    public function validate(Ruled $element): bool;

    public function setMessage(?string $message = null): ?string;

    public function getMessage(): ?string;
}
