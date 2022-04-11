<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\FillableInterface;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

class Radio extends Element implements FillableInterface, Ruled
{
    use Fill;
    use Description;
    use Rules;

    private const DEFAULT_PREFIX = 'rb_';

    protected string $type = 'radio';
    private static string $prefix_id = 'rb_';


    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttrs(AttributeFactory::createFromArray([
            'id' => $this->getPrefixId() . $name,
            'value' => $name,
        ]));
        $this->removeAttr('name');
    }

    public function setPrefixId(string $prefix): self
    {
        static::$prefix_id = $prefix;
        $this->setAttrs(AttributeFactory::createFromArray([
            'id' => static::$prefix_id . $this->getName()
        ]));

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

    /**
     *
     * @param mixed $value
     * @return $this
     */
    protected function setDefault($value = null): self
    {
        $this->defaultValue = $value ?? $this->getForm()->getDefaultsHandler()->getValue($this->getName());

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
        $this->setAttr($this->getAttr('id')->withName('for'), Form::ATTRIBUTES_LABEL);
        $this->setAttr(AttributeFactory::create('name',$this->getParentName()));
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}><label{$this->getAttributesString(Form::ATTRIBUTES_LABEL)}>{$this->getLabel()}</label>";
    }
}
