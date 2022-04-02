<?php

declare(strict_types=1);

namespace Enjoys\Forms;

class FillHandler
{

    private array $attributes = [];
    /**
     * @var mixed
     */
    private $value = null;
    private ?string $label = null;

    /**
     *
     * @param mixed $value
     * @param mixed $label
     * @param bool $useTitleAsValue
     */
    public function __construct($value, $label, $useTitleAsValue = false)
    {
        if (is_array($label)) {
            $this->label = (string)($label[0] ?? null);

            if (isset($label[1]) && is_array($label[1])) {
                $this->attributes = $label[1];
            }
        }

        $this->label ??= (string)$label;

        /** @since 2.4.0 */
        if (is_string($value)) {
            $this->value = \trim($value);
        }

        /** @since 2.4.0 */
        if (is_int($value) && $useTitleAsValue) {
            $this->value = $this->label;
        }

        /** @since 3.4.0 */
        if (is_int($value) && !$useTitleAsValue) {
            $this->value = $value;
        }
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getValue(): ?string
    {
        return (string)$this->value;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
}
