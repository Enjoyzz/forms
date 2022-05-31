<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Request;

/**
 * @example
 * new Equal($message, ['expect']); or
 * new Equal($message, (array) 'expect'); or
 * new Equal($message, ['expect', 1, '255']);
 */
class Equal implements RuleInterface
{
    use Request;

    private string $message;
    private array $params;


    /**
     * @param array<array-key, scalar>|scalar $params
     * @param string|null $message
     */
    public function __construct(mixed $params, string $message = null)
    {

        $this->params = (array) $params;
        $this->message = $message ?? 'Допустимые значения (указаны через запятую): ' . \implode(', ', $this->params);
    }


    /**
     * @psalm-suppress PossiblyNullReference
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool
    {

        $method = $this->getRequest()->getRequest()->getMethod();
        $requestData = match (strtolower($method)) {
            'get' => $this->getRequest()->getQueryData()->toArray(),
            'post' => $this->getRequest()->getPostData()->toArray(),
            default => []
        };

        /** @var mixed $value */
        $value = \getValueByIndexPath($element->getName(), $requestData);

        if (false === $this->check($value)) {
            $element->setRuleError($this->message);
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return array-key|bool
     * @noinspection PhpMissingReturnTypeInspection
     */
    private function check(mixed $value)
    {

        if ($value === false) {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $_val) {
                if (false === $this->check($_val)) {
                    return false;
                }
            }
            return true;
        }

        return array_search($value, $this->params);
    }
}
