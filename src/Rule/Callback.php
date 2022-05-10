<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;

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
 *
 * @example Callback anonimous class
 *
 *  $form->text('foo')->addRule('callback', null, new class {
 *      public $execute = 'test';
 *      public function test() {
 *          return false;
 *      }
 *  });
 *
 *
 * @author Enjoys
 */
class Callback extends Rules implements RuleInterface
{
    public function setMessage(?string $message = null): ?string
    {
        if (is_null($message)) {
            $message = 'Ошибка';
        }
        return parent::setMessage($message);
    }

    public function validate(Ruleable $element): bool
    {
        if ($this->check() === false) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @throws \ReflectionException
     */
    private function check(): bool
    {
        /** @var callable(mixed...):bool $callback */
        $callback = $this->getParam(0);

        $params = $this->getParams();
        array_shift($params);
        /** @psalm-suppress PossiblyNullFunctionCall */
        return call_user_func($callback, ...$params);
    }
}
