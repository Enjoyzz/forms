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
     * rows Высота поля в строках текста.
     */
    public function setRows($rows): self
    {
        $this->setAttribute('rows', (string) $rows);
        return $this;
    }

    /**
     * cols Ширина поля в символах.
     */
    public function setCols($cols): self
    {
        $this->setAttribute('cols', (string) $cols);
        return $this;
    }

    public function baseHtml(): string
    {
        $value = $this->getAttribute('value');
        if ($value !== false) {
            $this->setValue($value);
            $this->removeAttribute('value');
        }
        return "<textarea{$this->getAttributesString()}>{$this->getValue()}</textarea>";
    }
}
