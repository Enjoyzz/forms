<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule\UploadCheck;

use ByteUnits\Binary;
use Enjoys\Forms\Interfaces\Ruleable;
use Psr\Http\Message\UploadedFileInterface;

final class MaxsizeCheck implements UploadCheckInterface
{
    private UploadedFileInterface|false $value;
    private Ruleable $element;
    private int|string $thresholdSize;
    private ?string $message;

    public function __construct(
        false|UploadedFileInterface $value,
        Ruleable $element,
        int|string $thresholdSize,
        ?string $message = null
    ) {
        $this->value = $value;
        $this->element = $element;
        $this->thresholdSize = $thresholdSize;
        $this->message = $message;
    }

    public function check(): bool
    {
        if ($this->value === false) {
            return true;
        }


        $file_size = $this->value->getSize() ?? 0;

        if (is_null($this->message)) {
            $this->message = 'Размер файла (' . Binary::bytes($file_size)->format(null, " ") . ')'
                . ' превышает допустимый размер: ' . Binary::bytes($this->thresholdSize)->format(null, " ");
        }

        if ($file_size > $this->thresholdSize) {
            $this->element->setRuleError($this->message);
            return false;
        }
        return true;
    }
}
