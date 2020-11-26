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

namespace Enjoys\Forms\Traits;

/**
 *
 * @author Enjoys
 */
trait Rules
{

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
     * Проверяет обязателен ли элемент для заполнения/выбора или нет
     * true - обязателен
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     *
     * @param string $ruleClass
     * @param string $message
     * @param array $params
     * @return $this
     */
    public function addRule(string $ruleClass, ?string $message = null, $params = [])
    {
        //$class = "\Enjoys\Forms\Rule\\" . \ucfirst($rule);
        if (!class_exists($ruleClass)) {
            throw new \Enjoys\Forms\Exception\ExceptionRule(
                sprintf('Rule [%s] not found', $ruleClass)
            );
        }
        $rule = new $ruleClass($message, $params);
        $rule->setRequest($this->request);

        //установка обязательности элемента
        if ($ruleClass === \Enjoys\Forms\Rules::REQUIRED) {
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
}
