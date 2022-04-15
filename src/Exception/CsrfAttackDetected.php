<?php

declare(strict_types=1);

namespace Enjoys\Forms\Exception;

final class CsrfAttackDetected extends ExceptionRule
{
    public function __construct(string $message = "CSRF Attack detected", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
