<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rule\UploadCheck\UploadCheckInterface;
use Enjoys\Forms\Rules;
use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\Assert;

class Upload extends Rules implements RuleInterface
{

    /**
     * @psalm-suppress PossiblyNullReference
     * @param Ruleable&Element $element
     * @return bool
     * @throws ExceptionRule
     */
    public function validate(Ruleable $element): bool
    {
        /** @var UploadedFileInterface|false $value */
        $value = \getValueByIndexPath($element->getName(), $this->getRequest()->getFilesData()->toArray());

        if (false === $this->check($value, $element)) {
            return false;
        }
        return true;
    }

    private function check(UploadedFileInterface|false $value, Ruleable $element): bool
    {
        foreach ($this->getParams() as $rule => $options) {

            if (is_int($rule) && is_string($options)) {
                $rule = $options;
                $ruleOpts = null;
            }

            /** @var class-string<UploadCheckInterface> $className */
            $className = sprintf('\Enjoys\Forms\Rule\UploadCheck\%sCheck', ucfirst($rule));
            Assert::classExists($className, sprintf('Unknown Check Upload: [%s]', $className));
            return (new $className($value, $element, $options))->check();
        }
        return true;
    }
}
