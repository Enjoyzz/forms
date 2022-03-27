<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rules;

/**
 * Description of Required
 *
 * $form->text($name, $title)->addRule('required');
 * $form->text($name, $title)->addRule('required', $message);
 *
 */
class Required extends Rules implements RuleInterface
{

    public function setMessage(?string $message = null): ?string
    {
        if (is_null($message)) {
            $message = 'Обязательно для заполнения, или выбора';
        }
        return parent::setMessage($message);
    }
    /**
     * @psalm-suppress PossiblyNullReference
     * @param Ruled&Element $element
     * @return bool
     */
    public function validate(Ruled $element): bool
    {
        $requestData = match(strtolower($this->getRequestWrapper()->getRequest()->getMethod())){
            'get' => $this->getRequestWrapper()->getQueryData()->getAll(),
            'post' => $this->getRequestWrapper()->getPostData()->getAll(),
            default => []
        };
        $_value = \getValueByIndexPath($element->getName(), $requestData);
        if (!$this->check($_value)) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }



    private function check($value): bool
    {
        if (is_array($value)) {
            return count($value) > 0;
        }
        return \trim((string) $value) != '';
    }
}
