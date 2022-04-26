<?php

namespace Enjoys\Forms\Traits;

trait Description
{

    private ?string $description = null;

    /**
     * @return $this
     */
    public function setDescription(?string $description = null)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
