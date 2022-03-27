<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Elements\Group;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rule\RuleInterface;


class Validator
{

    /**
     * Валидация формы
     * @param array $elements
     * @return bool
     */
    public static function check(array $elements): bool
    {
        $_validate = true;
        /** @var Ruled $element */
        foreach ($elements as $element) {
            if ($element instanceof Group) {
                $_validate = (!self::check($element->getElements())) ? false : $_validate;
                continue;
            }

            if (!method_exists($element, 'getRules')) {
                continue;
            }

            /** @var RuleInterface $rule */
            foreach ($element->getRules() as $rule) {
                if (!$rule->validate($element)) {
                    $_validate = false;
                }
            }
        }
        return $_validate;
    }
}
