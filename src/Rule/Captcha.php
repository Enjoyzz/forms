<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Request;

/**
 * Description of Captcha
 *
 * Captcha element will automatically set the rule(s)
 * $form->captcha();
 * Enjoy!
 */
class Captcha implements RuleInterface
{
    use Request;

    private ?string $message;

    public function __construct(?string $message = null)
    {
        $this->message = $message ;
    }

    /**
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool
    {
        /** @var \Enjoys\Forms\Elements\Captcha $element */
        if ($element->validate() === false) {
            $element->setRuleError($this->message);
            return false;
        }
        return true;
    }


    public function getMessage(): ?string
    {
        return $this->message;
    }
}
