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

use Enjoys\Forms\Interfaces;
use Enjoys\Forms\Traits\Attributes;
use Enjoys\Forms\Traits\Request;

/**
 * Class Element
 *
 *
 * @author Enjoys
 */
class Element implements Interfaces\Element
{
    use Attributes;
    use Request;

    /**
     *
     * @var string
     */
    protected string $type;

    /**
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     *
     * @var string|null
     */
    protected ?string $id = null;

    /**
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     *
     * @var string|null
     */
    protected ?string $value = null;

    /**
     * Флаг для обозначения обязательности заполнения этого элемента или нет
     * @var bool 
     */
    protected bool $required = false;

    /**
     * Когда $rule_error === true выводится это сообщение
     * @var string|null
     */
    private ?string $rule_error_message = null;

    /**
     * Если true - проверка validate не пройдена
     * @var bool
     */
    private bool $rule_error = false;

    /**
     * Список правил для валидации
     * @var array
     */
    protected array $rules = [];

    /**
     * 
     * @var FormDefaults|null
     */
    protected FormDefaults $formDefaults;

    /**
     *
     * @param string $name
     * @param string $title
     */
    public function __construct(FormDefaults $formDefaults, string $name, string $title = null)
    {

        $this->initRequest();

        $this->formDefaults = $formDefaults;

        $this->setName($name);

        if (!is_null($title)) {
            $this->setTitle($title);
        }
    }

    /**
     *
     * @param string $name
     * @return \self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        $this->setId($this->name);
        $this->setAttribute('name', $this->name);

        $this->setDefault();

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return \self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        $this->setAttribute('id', $this->id);
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
     * @param string $value
     * @return \self
     */
    protected function setValue(string $value): self
    {
        if ($this->getAttribute('value') !== false) {
            return $this;
        }
        $this->value = $value;
        $this->setAttribute('value', \htmlspecialchars( $this->value, ENT_QUOTES));
        return $this;
    }

    /**
     *
     * @param string $title
     * @return \self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     *
     * @param string $description
     * @return \self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     *
     * @param FormDefaults $defaults
     */
    public function setFormDefaults(FormDefaults $defaults)
    {
        $this->formDefaults = $defaults;
        $this->setDefault();
    }

    /**
     * @return \self
     */
    protected function setDefault(): self
    {
        $value = $this->formDefaults->getValue($this->getName());

        if (is_array($value)) {
            $this->setValue($value[0]);
        }

        if (is_string($value) || is_numeric($value)) {
            $this->setValue($value);
        }
        return $this;
    }

    /**
     *
     * @param string $ruleName
     * @param string $message
     * @param array $params
     * @return $this
     */
    public function addRule(string $ruleName, ?string $message = null, $params = [])
    {
        $class = "\Enjoys\Forms\Rule\\" . \ucfirst($ruleName);

        $rule = new $class($message, $params);
        $rule->initRequest($this->request);

        //установка обязательности элемента
        if (\strtolower($ruleName) === \strtolower(Rules::REQUIRED)) {
            $this->required = true;
            
            /** 
             * @todo Если обязателен элемент добавить required аттрибут для проверки на стороне браузера
             * пока Отключено
             */
            //$this->setAttribute('required');
        }

        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Проверяет обязателен ли элемент для заполнения/выбора или нет
     * true - обязателен
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Если валидация не пройдена, вызывается этот медот, устанавливает сообщение
     * ошибки и устанавливает флаг того что проверка не пройдена
     * @param string|null $message
     * @return void
     */
    public function setRuleError(?string $message): void
    {
        $this->rule_error = true;
        $this->rule_error_message = $message;
    }

    /**
     *
     * @return string|null
     */
    public function getRuleErrorMessage(): ?string
    {
        return $this->rule_error_message;
    }

    /**
     * Проверка валидации элемента, если true валидация не пройдена 
     * @return bool
     */
    public function isRuleError(): bool
    {
        return $this->rule_error;
    }

    /**
     * Возвращает списов всех правил валидации, установленных для элемента
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
    
    public function baseHtml()
    {
        return "<input type=\"{$this->getType()}\"{$this->getAttributes()}>";
    }
}
