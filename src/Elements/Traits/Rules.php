<?php

declare(strict_types=1);


namespace Enjoys\Forms\Elements\Traits;


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
        $rule->setServerRequest($this->getServerRequest());

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