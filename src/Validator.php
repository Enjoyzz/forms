<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Elements\Group;
use Enjoys\Forms\Interfaces\Ruled;


class Validator
{

    /**
     * Валидация формы
     * @param Element[] $elements
     * @return bool
     */
    public static function check(array $elements): bool
    {
        $_validate = true;

        foreach ($elements as $element) {
            if ($element instanceof Group) {
                $_validate = (!self::check($element->getElements())) ? false : $_validate;
                continue;
            }

            if (!($element instanceof Ruled)) {
                continue;
            }


            foreach ($element->getRules() as $rule) {
                if (!$rule->validate($element)) {
                    $_validate = false;
                }
            }
        }
        return $_validate;
    }
}
