<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Traits\Fill;

/**
 * Class Option
 * @package Enjoys\Forms\Elements
 */
class Option extends Element implements FillableInterface
{
    use Fill;

    protected string $type = 'option';


    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttributes([
            'value' => $name,
            'id' => $name
        ]);
        $this->removeAttribute('name');
    }

    /**
     *
     * @param mixed $value
     * @return $this
     */
    protected function setDefault($value = null): self
    {
        if (is_array($value)) {
            if (in_array($this->getAttribute('value'), $value)) {
                $this->setAttribute('selected');
                return $this;
            }
        }

        if (is_string($value) || is_numeric($value)) {
            if ($this->getAttribute('value') == $value) {
                $this->setAttribute('selected');
                return $this;
            }
        }
        return $this;
    }

    public function baseHtml(): string
    {
        $this->setAttributes($this->getAttributes('fill'));
        return "<option{$this->getAttributesString()}>{$this->getLabel()}</option>";
    }
}
