<?php

declare(strict_types=1);


namespace Enjoys\Forms\Rule\UploadCheck;


use Enjoys\Forms\Interfaces\Ruleable;
use Psr\Http\Message\UploadedFileInterface;

final class RequiredCheck implements UploadCheckInterface
{


    private UploadedFileInterface|false $value;
    private Ruleable $element;
    private ?string $message;

    public function __construct(false|UploadedFileInterface $value, Ruleable $element, string $message = null)
    {
        $this->value = $value;
        $this->element = $element;
        $this->message = $message;
    }

    public function check(): bool
    {
        if (is_null($this->message)) {
            $this->message = 'Выберите файл для загрузки';
        }

        if ($this->value === false || $this->value->getError() == \UPLOAD_ERR_NO_FILE) {
            $this->element->setRuleError($this->message);
            return false;
        }

        return true;
    }
}
