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

class Radio extends Element implements Fillable, Ruleable, Descriptionable
{
    use Fill;
    use Description;
    use Rules;

    protected string $type = 'radio';
    private static ?string $prefix_id = null;
    private bool $parent;
    private string $originalName;


    public function __construct(string $name, string $title = null, bool $parent = true)
    {
        $this->parent = $parent;
        $this->originalName = $name;

        parent::__construct($name, $title);


        /** @psalm-suppress PossiblyNullOperand */
        $this->setAttributes(
            AttributeFactory::createFromArray([
                'id' => self::$prefix_id . $this->originalName,
                'value' => $name,
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

    /**
     *
     * @param mixed $value
     * @return $this
     * @psalm-suppress PossiblyNullReference
     */
    protected function setDefault(mixed $value = null): static
    {
        $this->setDefaultValue($value);

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

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function baseHtml(): string
    {
        $this->setAttribute(
            $this->getAttribute('id')->withName('for'),
            Form::ATTRIBUTES_LABEL
        );
        $this->setAttribute(AttributeFactory::create('name', $this->getParentName()));
        return sprintf(
            '<input type="%s"%s><label%s>%s</label>',
            $this->getType(),
            $this->getAttributesString(),
            $this->getAttributesString(Form::ATTRIBUTES_LABEL),
            $this->getLabel() ?? ''
        );
    }
}
