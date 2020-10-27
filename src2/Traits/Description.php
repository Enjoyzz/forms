<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Traits;

/**
 * Description of Description
 *
 * @author Enjoys
 */
trait Description
{

    protected ?string $description = null;

    /**
     *
     * @param string $description
     * @return \self
     */
    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
