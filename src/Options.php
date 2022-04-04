<?php

declare(strict_types=1);


namespace Enjoys\Forms;

use Enjoys\Traits;

final class Options
{

    use Traits\Options;

    private const ALLOWED_METHODS = ['GET', 'POST'];
    private string $method = 'POST';
    private ?string $action = null;

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method = null): void
    {
        if ($method === null){
            $this->method = 'POST';
            return;
        }

        $method = strtoupper($method);

        if (in_array($method, self::ALLOWED_METHODS)){
            $this->method = $method;
            return;
        }

        $this->method = 'POST';

    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

}
