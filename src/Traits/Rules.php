<?php

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\RuleInterface;

trait Rules
{
    /**
     * Когда $rule_error === true выводится это сообщение
     */
    private ?string $rule_error_message = null;

    /**
     * Если true - проверка validate не пройдена
     */
    private bool $rule_error = false;


    /**
     * Список правил для валидации
     * @var RuleInterface[]
     */
    protected array $rules = [];

    /**
     * Проверяет обязателен ли элемент для заполнения/выбора или нет
     * true - обязателен
     */
    public function isRequired(): bool
    {
        return $this->required;
    }


    /**
     * @throws ExceptionRule
     */
    public function addRule(string $ruleClass, mixed ...$params): static
    {

        if (!class_exists($ruleClass)) {
            throw new ExceptionRule(
                sprintf('Rule [%s] not found', $ruleClass)
            );
        }
        /** @var class-string<RuleInterface> $ruleClass */
        $rule = new $ruleClass(...$params);
        $rule->setRequest($this->getRequest());

        //установка обязательности элемента
        if ($ruleClass === \Enjoys\Forms\Rules::REQUIRED) {
            $this->required = true;

            /**
             * @todo Если обязателен элемент добавить required аттрибут для проверки на стороне браузера
             * Пока отключено
             */
            //$this->setAttribute('required');
        }

        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Если валидация не пройдена, вызывается этот метод, устанавливает сообщение
     * ошибки и устанавливает флаг того что проверка не пройдена
     */
    public function setRuleError(?string $message): void
    {
        $this->rule_error = true;
        $this->rule_error_message = $message;
    }

    public function getRuleErrorMessage(): ?string
    {
        return $this->rule_error_message;
    }

    /**
     * Проверка валидации элемента, если true валидация не пройдена
     */
    public function isRuleError(): bool
    {
        return $this->rule_error;
    }


    /**
     * Возвращает список всех правил валидации, установленных для элемента
     * @return RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function disableRules(): static
    {
        $this->rules = [];
        return $this;
    }
}
