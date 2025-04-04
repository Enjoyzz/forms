<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Closure;
use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;
use Webmozart\Assert\Assert;

class Textarea extends Element implements Ruleable, Descriptionable
{
    use Description;
    use Rules;

    protected string $type = 'textarea';


    private string $value;

    public function __construct(string $name, ?string $label = null)
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


    /**
     * Высота поля в строках текста.
     * @param int|Closure():int $rows
     * @return Textarea
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function setRows(int|Closure $rows): Textarea
    {
        if ($rows instanceof Closure) {
            $rows = $rows();
            Assert::integer($rows);
        }
        $this->setAttribute(AttributeFactory::create('rows', $rows));
        return $this;
    }

    /**
     * Ширина поля в символах.
     * @param int|Closure():int $cols
     * @return Textarea
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function setCols(int|Closure $cols): Textarea
    {
        if ($cols instanceof Closure) {
            $cols = $cols();
            Assert::integer($cols);
        }
        $this->setAttribute(AttributeFactory::create('cols', $cols));
        return $this;
    }

    public function baseHtml(): string
    {
        $value = $this->getAttribute('value');

        if ($value !== null) {
            $this->setValue($value->getValueString());
            $this->getAttributeCollection()->remove('value');
        }
        return "<textarea{$this->getAttributesString()}>{$this->getValue()}</textarea>";
    }
}
