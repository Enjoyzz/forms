<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;
use Webmozart\Assert\Assert;

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

    public function setValue(?string $value): Textarea
    {
        $this->value = $value ?? '';
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function getValidatedAttribute(mixed $value): mixed
    {
        if ($value instanceof \Closure){
            $value = $value();
        }
        Assert::numeric($value);
        return $value;
    }

    /**
     * Высота поля в строках текста.
     */
    public function setRows(mixed $rows): Textarea
    {
        $value = $this->getValidatedAttribute($rows);
        $this->setAttr(Attribute::create('rows', $value));
        return $this;
    }

    /**
     * Ширина поля в символах.
     */
    public function setCols(mixed $cols): Textarea
    {
        $value = $this->getValidatedAttribute($cols);
        $this->setAttr(Attribute::create('cols', $value));
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
