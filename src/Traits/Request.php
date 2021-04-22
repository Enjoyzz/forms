<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Http\ServerRequest;
use Enjoys\Http\ServerRequestInterface;

/**
 * Trait Request
 * @package Enjoys\Forms\Traits
 */
trait Request
{

    protected ?ServerRequestInterface $request = null;

    public function getRequest(): ServerRequestInterface
    {
        if ($this->request === null) {
            $this->setRequest();
        }
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request = null): void
    {
        $this->request = $request ?? new ServerRequest();
    }
}
