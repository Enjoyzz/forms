<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rule\UploadCheck\UploadCheckInterface;
use Enjoys\Forms\Traits\Request;
use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\Assert;

class Upload implements RuleInterface
{
    use Request;

    public const REQUIRED = 'required';
    public const EXTENSIONS = 'extensions';

    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @psalm-suppress MixedArgument
     * @param Ruleable&Element $element
     * @return bool
     */
    public function validate(Ruleable $element): bool
    {
        /** @var UploadedFileInterface[] $uploadedFiles */
        $uploadedFiles = $this->getUploadedFiles(
            \getValueByIndexPath($element->getName(), $this->getRequest()->getFilesData()->toArray())
        );

        foreach ($uploadedFiles as $uploadedFile) {
            if (false === $this->check($uploadedFile, $element)) {
                return false;
            }
        }
        return true;
    }

    private function check(UploadedFileInterface|false $value, Ruleable $element): bool
    {
        /** @var array|scalar $options */
        foreach ($this->params as $rule => $options) {
            if (is_int($rule) && is_string($options)) {
                $rule = $options;
                $options = [];
            }


            /** @var string $rule */
            $className = sprintf('\Enjoys\Forms\Rule\UploadCheck\%sCheck', ucfirst($rule));

            /** @var class-string<UploadCheckInterface> $className */
            Assert::classExists($className, sprintf('Unknown Check Upload: [%s]', $className));

            if ((new $className($value, $element, ...(array)$options))->check() === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param false|UploadedFileInterface[]|UploadedFileInterface $item
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    private function getUploadedFiles($item): array
    {
        if (is_array($item)) {
            return $item;
        }
        return [$item];
    }
}
