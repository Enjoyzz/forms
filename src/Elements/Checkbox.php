<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;


class Checkbox extends Element implements FillableInterface, Ruled
{
    use Fill;
    use Description;
    use Rules;

    private const DEFAULT_PREFIX = 'cb_';

    protected string $type = 'checkbox';
    private static string $prefix_id = 'cb_';


    public function __construct(string $name, string $title = null)
    {
        $construct_name = $name;
        if (\substr($name, -2) !== '[]') {
            $construct_name = $name . '[]';
        }
        parent::__construct($construct_name, $title);

        $this->setAttrs(
            AttributeFactory::createFromArray([
                'id' => $this->getPrefixId() . $name,
                'value' => $name,
            ])
        );
        $this->getAttributeCollection()->remove('name');
    }


    public function setPrefixId(string $prefix): self
    {
        static::$prefix_id = $prefix;
        $this->setAttr(AttributeFactory::create('id', static::$prefix_id . $this->getName()));
        return $this;
    }

    public function getPrefixId(): string
    {
        return static::$prefix_id;
    }

    public function resetPrefixId(): void
    {
        $this->setPrefixId(self::DEFAULT_PREFIX);
    }


    protected function setDefault($value = null): self
    {
        $this->defaultValue = $value ?? $this->getForm()->getDefaultsHandler()->getValue(trim($this->getName()));


        if (is_array($value)) {
            if (in_array($this->getAttr('value')->getValueString(), $value)) {
                $this->setAttr(AttributeFactory::create('checked'));
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttr('value')->getValueString() == $value) {
                $this->setAttr(AttributeFactory::create('checked'));
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $this->setAttr(AttributeFactory::create('for', $this->getAttr('id')->getValueString()), Form::ATTRIBUTES_LABEL);
        $this->setAttrs(AttributeFactory::createFromArray(['name' => $this->getParentName()]));
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}><label{$this->getAttributesString(Form::ATTRIBUTES_LABEL)}>{$this->getLabel()}</label>";
    }
}
