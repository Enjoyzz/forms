<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rules;

/**
 * Description of Captcha
 *
 * Captcha element will automatically set the rule(s)
 * $form->captcha();
 * Enjoy!
 */
class Captcha extends Rules implements RuleInterface
{
    /**
     * @param Ruled&Element $element
     * @return bool
     */
    public function validate(Ruled $element): bool
    {
        /** @var \Enjoys\Forms\Elements\Captcha $element */
        return $element->validate();
    }
}
