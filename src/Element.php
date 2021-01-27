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

namespace Enjoys\Forms;

use Enjoys\Forms\Traits\Attributes;
use Enjoys\Forms\Traits\Request;

/**
 * Class Element
 *
 *
 * @author Enjoys
 */
abstract class Element implements ElementInterface
{
    use Attributes;
    use Request;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @var string
     */
    protected string $name;

    /**
     *
     * @var string
     */
    protected string $type = '';

    /**
     *
     * @var string|null
     */
    protected ?string $label = null;

    /**
     * Флаг для обозначения обязательности заполнения этого элемента или нет
     * @var bool
     */
    protected bool $required = false;

    /**
     *
     * @var Form|null
     */
    protected ?Form $form = null;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @param string $name
     * @param string $label
     */
    public function __construct(string $name, string $label = null)
    {

        $this->setName($name);

        if (!is_null($label)) {
            $this->setLabel($label);
        }
    }

    /**
     * @param \Enjoys\Forms\Form $form
     *
     * @return void
     */
    public function setForm(Form $form): void
    {
        $this->form = $form;
        $this->setDefault();
    }

    /**
     *
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form ?? new Form();
    }

    /**
     *
     * @return void
     */
    public function unsetForm(): void
    {
        $this->form = null;
    }

    /**
     * @return mixed|void
     */
    public function prepare()
    {
        $this->unsetForm();
    }

    /**
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    protected function setName(string $name): self
    {
        $this->name = $name;
        $this->setAttributes([
            'id' => $this->name,
            'name' => $this->name
        ]);

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @param string $title
     * @return $this
     */
    public function setLabel(?string $title = null): self
    {
        $this->label = $title;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return $this
     */
    protected function setDefault(): self
    {
        $value = $this->getForm()
                ->getDefaultsHandler()
                ->getValue($this->getName());

        if (is_array($value)) {
            $this->setAttributes([
                'value' => htmlspecialchars($value[0], ENT_QUOTES)
            ]);
        }

        if (is_string($value) || is_numeric($value)) {
            // $this->setValue($value);
            $this->setAttributes([
                'value' => htmlspecialchars($value, ENT_QUOTES)
            ]);
        }
        return $this;
    }

    public function baseHtml(): string
    {
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}>";
    }
}
