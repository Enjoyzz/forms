<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Interfaces\DefaultsHandlerInterface;

class DefaultsHandler implements DefaultsHandlerInterface
{
    private array $defaults = [];

    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    public function setData(array $data = []): void
    {
        $this->defaults = $data;
    }

    /**
     *
     * @return mixed
     */
    public function getDefaults(): mixed
    {
        return $this->get();
    }

    /**
     *
     * @return mixed
     */
    public function getValue(?string $param): mixed
    {
        return $this->get($param);
    }


    private function get(?string $param = null): mixed
    {
        if ($param === null) {
            return $this->defaults;
        }
        return \getValueByIndexPath($param, $this->defaults);
    }
}
