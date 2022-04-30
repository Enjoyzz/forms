<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

class Checkbox extends Element implements Fillable, Ruleable, Descriptionable
{
    use Fill;
    use Description;
    use Rules;

    protected string $type = 'checkbox';
    private static ?string $prefix_id = null;
    private bool $parent;
    private string $originalName;


    public function __construct(string $name, string $title = null, bool $parent = true)
    {
        $this->parent = $parent;
        $this->originalName = $name;

        $construct_name = (!str_ends_with($name, '[]')) ? $name . '[]' : $name;

        parent::__construct($construct_name, $title);

        $this->setAttributes(
            AttributeFactory::createFromArray([
                'id' => self::$prefix_id . $this->originalName,
                'value' => $this->originalName,
            ])
        );

        $this->setPrefixId($this->originalName . '_');
        $this->removeAttribute('name');
    }


    public function setPrefixId(string $prefix): self
    {
        if ($this->parent) {
            static::$prefix_id = $prefix;
            $this->setAttribute(AttributeFactory::create('id', $this->originalName));
        }
        return $this;
    }

    public function getPrefixId(): ?string
    {
        return static::$prefix_id;
    }

    protected function setDefault(mixed $value = null): self
    {
        if ($value === null) {
            $value = $this->getForm()->getDefaultsHandler()->getValue($this->getName());
        }
        $this->defaultValue = $value;

        if (is_array($value)) {
            if (in_array($this->getAttribute('value')->getValueString(), $value)) {
                $this->setAttribute(AttributeFactory::create('checked'));
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttribute('value')->getValueString() == $value) {
                $this->setAttribute(AttributeFactory::create('checked'));
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $this->setAttribute(AttributeFactory::create('for', $this->getAttribute('id')->getValueString()), Form::ATTRIBUTES_LABEL);
        $this->setAttributes(AttributeFactory::createFromArray(['name' => $this->getParentName()]));
        return sprintf(
            '<input type="%s"%s><label%s>%s</label>',
            $this->getType(),
            $this->getAttributesString(),
            $this->getAttributesString(Form::ATTRIBUTES_LABEL),
            $this->getLabel()
        );
    }
}
