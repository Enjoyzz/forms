<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Form;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

/**
 * Class Checkbox
 * @package Enjoys\Forms\Elements
 */
class Checkbox extends Element implements FillableInterface
{
    use Fill;
    use Description;
    use Rules;

    private const DEFAULT_PREFIX = 'cb_';

    /**
     *
     * @var string
     */
    protected string $type = 'checkbox';
    private static string $prefix_id = 'cb_';


    public function __construct(string $name, string $title = null)
    {
        $construct_name = $name;
        if (\substr($name, -2) !== '[]') {
            $construct_name = $name . '[]';
        }
        parent::__construct($construct_name, $title);

        $this->setAttributes(
            [
                'value' => $name,
                'id' => $this->getPrefixId() . $name
            ]
        );
        $this->removeAttribute('name');
    }


    public function setPrefixId(string $prefix): self
    {
        static::$prefix_id = $prefix;
        $this->setAttribute('id', static::$prefix_id . $this->getName());
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
        $this->defaultValue = $value ?? $this->getForm()->getDefaultsHandler()->getValue(trim($this->getName()));


        if (is_array($value)) {
            if (in_array($this->getAttribute('value'), $value)) {
                $this->setAttribute('checked');
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttribute('value') == $value) {
                $this->setAttribute('checked');
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $this->setAttribute('for', $this->getAttribute('id'), Form::ATTRIBUTES_LABEL);
        $this->setAttributes($this->getAttributes('fill'), Form::ATTRIBUTES_LABEL);


        $this->setAttributes(['name' => $this->getParentName()]);
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}><label{$this->getAttributesString(Form::ATTRIBUTES_LABEL)}>{$this->getLabel()}</label>\n";
    }
}
