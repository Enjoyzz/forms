<?php

declare(strict_types=1);


namespace Enjoys\Forms\Rule\UploadCheck;


use Enjoys\Forms\Interfaces\Ruleable;
use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\Assert;

final class ExtensionsCheck implements UploadCheckInterface
{


    private UploadedFileInterface|false $value;
    private Ruleable $element;
    private array|string $options;

    public function __construct(false|UploadedFileInterface $value, Ruleable $element, array|string $options)
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

        Assert::string( $parsed['param']);
        $expected_extensions = \array_map('trim', \explode(",", $parsed['param']));

        $message = $parsed['message'];

        $extension = pathinfo($this->value->getClientFilename() ?? '', PATHINFO_EXTENSION);

        if (is_null($message)) {
            $message = 'Загрузка файлов с расширением .' . $extension . ' запрещена';
        }


        if (!in_array($extension, $expected_extensions)) {
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
