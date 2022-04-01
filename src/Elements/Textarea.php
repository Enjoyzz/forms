<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
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

    public function setValue(?string $value): self
    {
        $this->value = $value ?? '';
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Высота поля в строках текста.
     */
    public function setRows(mixed $rows): self
    {
        $this->setAttr(Attribute::create('rows', $rows));
        return $this;
    }

    /**
     * Ширина поля в символах.
     */
    public function setCols(mixed $cols): Textarea
    {
        $this->setAttr(Attribute::create('cols', $cols));
        return $this;
    }

    public function baseHtml(): string
    {
        $value = $this->getAttr('value');

        if ($value !== null) {
            $this->setValue($value->getValueString());
            $this->getAttributeCollection()->remove('value');
        }
        return "<textarea{$this->getAttributesString()}>{$this->getValue()}</textarea>";
    }
}
