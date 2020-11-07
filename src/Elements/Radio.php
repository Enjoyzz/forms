<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;

/**
 * Class Radio
 *
 * @author Enjoys
 *
 */
class Radio extends Element implements \Enjoys\Forms\FillableInterface
{ 
    use Fill;
    use \Enjoys\Forms\Traits\Description;
    use \Enjoys\Forms\Traits\Rules;

    private const DEFAULT_PREFIX = 'rb_';

    /**
     *
     * @var string
     */
    protected string $type = 'radio';
    private static string $prefix_id = 'rb_';
    
    /**
     *
     * @var mixed 
     */
    protected $defaults = '';

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttributes([
            'value' => $name,
            'id' => $this->getPrefixId() . $name
        ]);
        $this->removeAttribute('name');
    }

    public function setPrefixId(string $prefix): self
    {
        static::$prefix_id = $prefix;
        $this->setAttributes([
            'id' => static::$prefix_id . $this->getName()
        ]);

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
        $this->defaults = $value ?? $this->getForm()->getDefaultsHandler()->getValue($this->getName());

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
        $this->setAttribute('for', $this->getAttribute('id'), \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
        $this->setAttributes($this->getAttributes('fill'), \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
        $this->setAttributes(['name' => $this->getParentName()]);
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}><label{$this->getAttributesString(\Enjoys\Forms\Form::ATTRIBUTES_LABEL)}>{$this->getLabel()}</label>\n";
    }
}
