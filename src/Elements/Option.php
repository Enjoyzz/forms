<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Traits\Fill;

class Option extends Element implements FillableInterface
{
    use Fill;

    protected string $type = 'option';


    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttrs(
            Attribute::createFromArray([
                'id' => $name,
                'value' => $name,
            ])
        )
            ->getAttributeCollection()
            ->remove('name')
        ;
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
                $this->setAttr(new Attribute('selected'));
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttr('value')->getValueString() == $value) {
                $this->setAttr(new Attribute('selected'));
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $this->setAttrs($this->getAttributeCollection('fill')->getIterator()->getArrayCopy());
        return "<option{$this->getAttributesString()}>{$this->getLabel()}</option>";
    }
}
