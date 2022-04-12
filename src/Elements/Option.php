<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\FillableInterface;
use Enjoys\Forms\Traits\Fill;

class Option extends Element implements FillableInterface
{
    use Fill;

    protected string $type = 'option';


    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttrs(
            AttributeFactory::createFromArray([
                'value' => $name,
            ])
        );
        $this->removeAttr('name');
        $this->removeAttr('id');
    }

    /**
     *
     * @param mixed $value
     * @return $this
     */
    protected function setDefault($value = null): self
    {
        if (is_array($value)) {
            if (in_array($this->getAttr('value')->getValueString(), $value)) {
                $this->setAttr(AttributeFactory::create('selected'));
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttr('value')->getValueString() == $value) {
                $this->setAttr(AttributeFactory::create('selected'));
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $this->setAttrs($this->getAttributeCollection('fill')->getIterator()->getArrayCopy());

        if ($this->getLabel() === null) {
            return "<option{$this->getAttributesString()}>";
        }

        return "<option{$this->getAttributesString()}>{$this->getLabel()}</option>";
    }
}
