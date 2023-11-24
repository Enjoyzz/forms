<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

trait Options
{
    /**
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    public function setOption(string $key, mixed $value, bool $useInternalMethods = true): self
    {
        $method = 'set' . ucfirst($key);
        if ($useInternalMethods === true && method_exists($this, $method)) {
            $this->$method($value);
            return $this;
        }

        $this->options[$key] = $value;
        return $this;
    }

    public function getOption(string $key, mixed $defaults = null, bool $useInternalMethods = true): mixed
    {
        $method = 'get' . ucfirst($key);
        if ($useInternalMethods === true && method_exists($this, $method)) {
            return $this->$method($defaults);
        }

        if (isset($this->options[$key])) {
            return $this->options[$key];
        }
        return $defaults;
    }

    /**
     * @param array<string, mixed> $options
     * @psalm-suppress MixedAssignment
     */
    public function setOptions(array $options = [], bool $useInternalMethods = true): self
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value, $useInternalMethods);
        }
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
