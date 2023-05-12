<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

/**
 * Trait Options
 * @package Enjoys\Traits
 */
trait Options
{
    /**
     *
     * @var array<mixed>
     */
    protected array $options = [];

    /**
     *
     * @param string $key
     * @param mixed $value
     * @param bool $useInternalMethods Если есть внутренние методы установки $this->setKey()  использовать их, если false игнорировать
     * @return $this
     * @since 1.3.0 добавлен флаг $useInternalMethods
     */
    public function setOption(string $key, $value, bool $useInternalMethods = true): self
    {
        $method = 'set' . ucfirst($key);
        if ($useInternalMethods === true && method_exists($this, $method)) {
            $this->$method($value);
            return $this;
        }

        $this->options[$key] = $value;
        return $this;
    }

    /**
     *
     * @param string $key
     * @param mixed $defaults
     * @param bool $useInternalMethods Если есть внутренние методы получения $this->getKey()  использовать их, если false игнорировать
     * @return mixed
     * @since 1.3.0 добавлен флаг $useInternalMethods
     */
    public function getOption(string $key, $defaults = null, bool $useInternalMethods = true)
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
     *
     * @param array<mixed> $options
     * @param bool $useInternalMethods Если есть внутренние методы установки $this->setKey()  использовать их, если false игнорировать
     * @return $this
     * @since 1.3.0 добавлен флаг $useInternalMethods
     */
    public function setOptions(array $options = [], bool $useInternalMethods = true): self
    {
        foreach ($options as $key => $value) {
            $this->setOption((string)$key, $value, $useInternalMethods);
        }
        return $this;
    }

    /**
     *
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
