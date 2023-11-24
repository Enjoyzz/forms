<?php

namespace Enjoys\Forms\Traits;

trait Description
{
    private ?string $description = null;

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
