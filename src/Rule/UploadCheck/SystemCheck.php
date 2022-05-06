<?php

declare(strict_types=1);


namespace Enjoys\Forms\Rule\UploadCheck;


use Enjoys\Forms\Interfaces\Ruleable;
use Psr\Http\Message\UploadedFileInterface;

final class SystemCheck implements UploadCheckInterface
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
        if ($this->value === false) {
            return true;
        }

        if (!in_array($this->value->getError(), array(\UPLOAD_ERR_OK, \UPLOAD_ERR_NO_FILE))) {
            $this->message =
                UploadCheckInterface::UPLOAD_ERROR_MESSAGE[$this->value->getError(
                )] ?? UploadCheckInterface::UPLOAD_ERROR_MESSAGE['unknown']
            ;
            $this->element->setRuleError($this->message);
            return false;
        }
        return true;
    }
}
