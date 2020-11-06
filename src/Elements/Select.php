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
use Enjoys\Forms\Traits\Rules;

/**
 * Description of Select
 *
 * @author Enjoys
 */
class Select extends Element
{
    use Fill;
    use Description;
    use Rules;

    /**
     *
     * @var string
     */
    protected string $type = 'option';
    private $defaults = null;

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
    }

    public function setMultiple(): self
    {
        $this->setAttribute('multiple');
        return $this;
    }

    private function isMultiple(): void
    {
        if ($this->getAttribute('multiple') !== false && \substr($this->getName(), -2) !== '[]') {
            $_id = $this->getAttribute('id');
            $this->setName($this->getName() . '[]');
            $this->setParentName($this->getName());
            //т.к. id уже переписан ,восстанавливаем его
            $this->setAttributes([
                'id' => $_id
            ]);
        }
    }

    /**
     * 
     * @param string $name
     * @param string|null $value
     * @param string $namespace
     * @return $this
     */
    public function setAttribute(string $name, ?string $value = null, string $namespace = 'general'): self
    {
        parent::setAttribute($name, $value, $namespace);
        $this->isMultiple();
        return $this;
    }

    /**
     * 
     * @param array $attributes
     * @param string $namespace
     * @return $this
     */
    public function setAttributes(array $attributes, string $namespace = 'general'): self
    {
        parent::setAttributes($attributes, $namespace);
        $this->isMultiple();
        return $this;
    }

    protected function setDefault(): self
    {
        $this->defaults = $this->getForm()->getDefaultsHandler()->getValue($this->getName());
        return $this;
    }

    /**
     * @since 2.4.0
     *
     * @param string $label Аттрибут label для optgroup
     * @param array $data Массив для заполнения в функции fill()
     * @param array $attributes Аттрибуты для optgroup (id и name аттрибуты автоматически удалены)
     * @return $this
     */
    public function setOptgroup(string $label = null, array $data = [], array $attributes = []): self
    {
        $optgroup = new Optgroup($label, $this->getName(), $this->defaults);
        $optgroup->setAttributes($attributes);
        $optgroup->fill($data);
        $this->elements[] = $optgroup;
        return $this;
    }
}
