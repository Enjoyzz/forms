<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Request;

/**
 * Description of Required
 *
 * $form->text($name, $title)->addRule('required');
 * $form->text($name, $title)->addRule('required', $message);
 *
 */
class Required implements RuleInterface
{
    use Request;

    private ?string $message;

    public function __construct(?string $message = null)
    {
        $this->message = $message ?? 'Обязательно для заполнения, или выбора';
    }


    /**
     * @psalm-suppress PossiblyNullReference
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool
    {
        $requestData = match (strtolower($this->getRequest()->getMethod())) {
            'get' => $this->getRequest()->getQueryParams(),
            'post' => $this->getRequest()->getParsedBody(),
            default => []
        };
        $_value = \getValueByIndexPath($element->getName(), $requestData);
        if (!$this->check($_value)) {
            $element->setRuleError($this->message);
            return false;
        }
        return true;
    }


    /**
     * @param mixed $value
     * @return bool
     */
    private function check(mixed $value): bool
    {
        if (is_array($value)) {
            return count($value) > 0;
        }
        return \trim((string) $value) != '';
    }
}
