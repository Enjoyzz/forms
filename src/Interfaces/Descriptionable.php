<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

interface Descriptionable
{
    /**
     * @return $this
     */
    public function setDescription(?string $description = null);
    public function getDescription(): ?string;
}
