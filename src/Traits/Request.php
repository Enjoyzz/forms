<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use HttpSoft\ServerRequest\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;

trait Request
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private ServerRequestInterface $request;

    public function setRequest(?ServerRequestInterface $request = null): void
    {
        $this->request = $request ?? ServerRequestCreator::createFromGlobals();
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
