<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;

trait Request
{
    private ServerRequestWrapper $request;

    public function setRequest(ServerRequestWrapper $request = null)
    {
        $this->request = $request ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
    }

    public function getRequest(): ServerRequestWrapper
    {
        return $this->request;
    }
}
