<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule\UploadCheck;

use Enjoys\Forms\Interfaces\Ruleable;
use Psr\Http\Message\UploadedFileInterface;

final class ExtensionsCheck implements UploadCheckInterface
{
    private UploadedFileInterface|false $value;
    private Ruleable $element;
    private array $expectedExtensions;
    private ?string $message;

    public function __construct(
        false|UploadedFileInterface $value,
        Ruleable $element,
        string $expectedExtensions,
        ?string $message = null
    ) {
        $this->value = $value;
        $this->element = $element;
        $this->expectedExtensions = \array_map('trim', \explode(",", \strtolower($expectedExtensions)));
        $this->message = $message;
    }

    public function check(): bool
    {
        if ($this->value === false) {
            return true;
        }
        $extension = pathinfo($this->value->getClientFilename() ?? '', PATHINFO_EXTENSION);

        if (is_null($this->message)) {
            $this->message = 'Загрузка файлов с расширением .' . $extension . ' запрещена';
        }


        if (!in_array(\strtolower($extension), $this->expectedExtensions, true)) {
            $this->element->setRuleError($this->message);
            return false;
        }
        return true;
    }
}
