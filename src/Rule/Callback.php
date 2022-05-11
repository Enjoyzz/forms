<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Request;

/**
 * Description of Callback
 *
 * @example Callback class
 * class Check {
 *      public function _check() {
 *          return false;
 *      }
 * }
 * $form ->text('foo')
 *       ->addRule('callback', null, [
 *              [
 *                  new Check(),
 *                  '_check'
 *              ]
 *      ]);
 *
 * @example Call static method
 *
 * class ClassTest {
 *      static public function isCheck() {
 *          return false;
 *      }
 * }
 * $form->text('foo')->addRule('callback', null, 'ClassTest::isCheck');
 *
 *
 * @example Callback function
 *
 * function check() {
 *      return false;
 * }
 * $form->text('foo')->addRule('callback', null, 'check');
 *
 *
 * @example Callback closure function (anonimous)
 *
 * $form->text('foo')->addRule('callback', null, function () {
 *      return false;
 * });
 *
 */
class Callback implements RuleInterface
{
    use Request;

    private string $message;
    private array $params;
    /**
     * @var callable(...mixed):bool $callback
     */
    private $callback;


    /**
     * @param string|null $message
     * @param callable(...mixed):bool $callback
     * @param mixed ...$params
     */
    public function __construct(?string $message, $callback, mixed ...$params)
    {
        $this->message = $message ?? 'Ошибка';
        $this->callback = $callback;
        $this->params = $params;
    }


    public function validate(Ruleable $element): bool
    {
        if ($this->check() === true) {
            return true;
        }

        $element->setRuleError($this->message);
        return false;
    }


    private function check(): bool
    {
        return call_user_func_array($this->callback, $this->params);
    }
}
