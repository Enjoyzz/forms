<?php

declare(strict_types=1);


namespace Enjoys\Forms\Rule\UploadCheck;


use ByteUnits\Binary;
use Enjoys\Forms\Interfaces\Ruleable;
use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\Assert;

final class MaxsizeCheck implements UploadCheckInterface
{


    private UploadedFileInterface|false $value;
    private Ruleable $element;
    private int|array|string $options;

    public function __construct(false|UploadedFileInterface $value, Ruleable $element, int|array|string $options)
    {
        $this->value = $value;
        $this->element = $element;
        $this->options = $options;
    }

    public function check(): bool
    {
        if ($this->value === false) {
            return true;
        }

        $parsed = $this->parseOptions($this->options);

        $threshold_size =  $parsed['param'];

        $message = $parsed['message'];

        $file_size = $this->value->getSize() ?? 0;

        if (is_null($message)) {
            $message = 'Размер файла (' . Binary::bytes($file_size)->format(null, " ") . ')'
                . ' превышает допустимый размер: ' . Binary::bytes($threshold_size)->format(null, " ");
        }

        if ($file_size > $threshold_size) {
            $this->element->setRuleError($message);
            return false;
        }
        return true;
    }

    private function parseOptions(int|array|string $opts): array
    {
        if (!is_array($opts)) {
            $opts = (array)$opts;
            $opts[1] = null;
        }
        list($param, $message) = $opts;

        Assert::nullOrString($message);

        return [
            'param' => $param,
            'message' => $message
        ];
    }
}
