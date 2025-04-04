<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Traits\Fill;

class Option extends Element implements Fillable
{
    use Fill;

    protected string $type = 'option';


    public function __construct(string $name, ?string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttributes(
            AttributeFactory::createFromArray([
                'value' => $name,
            ])
        );
        $this->removeAttribute('name');
        $this->removeAttribute('id');
    }

    /**
     *
     * @param mixed $value
     * @return $this
     */
    protected function setDefault(mixed $value = null): static
    {
        if (is_array($value)) {
            if (in_array($this->getAttribute('value')?->getValueString(), $value)) {
                $this->setAttribute(AttributeFactory::create('selected'));
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttribute('value')?->getValueString() == $value) {
                $this->setAttribute(AttributeFactory::create('selected'));
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        if ($this->getLabel() === null) {
            return sprintf("<option%s>", $this->getAttributesString());
        }

        return sprintf("<option%s>%s</option>", $this->getAttributesString(), $this->getLabel() ?? '');
    }
}
