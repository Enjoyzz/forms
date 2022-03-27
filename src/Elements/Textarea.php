<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;

class Textarea extends Element implements Ruled
{
    use Description;
    use Rules;

    protected string $type = 'textarea';


    private string $value;

    public function __construct(string $name, string $label = null)
    {
        parent::__construct($name, $label);
        $this->value = '';
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Высота поля в строках текста.
     */
    public function setRows(string|int $rows): self
    {
        $this->setAttribute('rows', (string)$rows);
        return $this;
    }

    /**
     * Ширина поля в символах.
     */
    public function setCols(int|string $cols): Textarea
    {
        $this->setAttribute('cols', (string)$cols);
        return $this;
    }

    public function baseHtml(): string
    {
        $value = $this->getAttribute('value');
        if (is_array($value) || is_null($value) || is_bool($value)) {
            $value = false;
        }
        if ($value !== false) {
            $this->setValue($value);
            $this->removeAttribute('value');
        }
        return "<textarea{$this->getAttributesString()}>{$this->getValue()}</textarea>";
    }
}
