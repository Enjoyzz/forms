<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Rule;
use Enjoys\Forms\Traits\Request;

class Rules
{
    use Request;

    public const CALLBACK = Rule\Callback::class;
    public const CAPTCHA = Rule\Captcha::class;
    public const EMAIL = Rule\Email::class;
    public const EQUAL = Rule\Equal::class;
    public const LENGTH = Rule\Length::class;
    public const REGEXP = Rule\Regexp::class;
    public const REQUIRED = Rule\Required::class;
    public const UPLOAD = Rule\Upload::class;


    private ?string $message;
    private array $params = [];

    public function __construct(?string $message = null, mixed $params = [])
    {
        $this->setParams($params);
        $this->message = $this->setMessage($message);
    }

    public function setParams(mixed $params): void
    {

        if (is_array($params)) {
            $this->params = $params;
            return;
        }
        $this->params[] = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array-key $key
     * @return mixed|null
     */
    public function getParam(int|string $key): mixed
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    public function setMessage(?string $message = null): ?string
    {
        return $this->message = $message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
