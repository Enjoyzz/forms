<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\ServerRequestWrapper;
use Enjoys\ServerRequestWrapperInterface;
use HttpSoft\ServerRequest\ServerRequestCreator;

trait Request
{
    private ServerRequestWrapperInterface $request;

    public function setRequest(ServerRequestWrapperInterface $request = null): void
    {
        $this->request = $request ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
    }

    public function getRequest(): ServerRequestWrapperInterface
    {
        return $this->request;
    }
}
