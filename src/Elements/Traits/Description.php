<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements\Traits;

trait Description
{

    private ?string $description = null;

    public function setDescription(?string $description = null): static
    {
        $this->description = $description;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }
}