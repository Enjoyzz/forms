<?php

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use ByteUnits\Binary;
use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;
use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\Assert;

class Upload extends Rules implements RuleInterface
{
    private array $systemErrorMessage = [
        'unknown' => "Unknown upload error",
        \UPLOAD_ERR_INI_SIZE => "Размер принятого файла превысил максимально допустимый размер,
            который задан директивой upload_max_filesize конфигурационного файла php.ini.",
        \UPLOAD_ERR_FORM_SIZE => "Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме.",
        \UPLOAD_ERR_PARTIAL => "Загружаемый файл был получен только частично.",
        \UPLOAD_ERR_NO_FILE => "Файл не был загружен",
        //Добавлено в PHP 5.0.3.
        \UPLOAD_ERR_NO_TMP_DIR => "Отсутствует временная папка.",
        //Добавлено в PHP 5.1.0.
        \UPLOAD_ERR_CANT_WRITE => "Не удалось записать файл на диск.",
        //Добавлено в PHP 5.2.0.
        \UPLOAD_ERR_EXTENSION => "File upload stopped by extension",
    ];


    private function getSystemMessage(int $error): string
    {
        if (isset($this->systemErrorMessage[$error])) {
            return $this->systemErrorMessage[$error];
        }
        return $this->systemErrorMessage['unknown'];
    }

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


    /**
     * @param false|UploadedFileInterface $value
     * @param Ruleable $element
     * @return bool
     * @throws ExceptionRule
     */
    private function check($value, Ruleable $element): bool
    {
        foreach ($this->getParams() as $rule => $ruleOpts) {
            if (is_int($rule) && is_string($ruleOpts)) {
                $rule = $ruleOpts;
                $ruleOpts = null;
            }
            $method = 'check' . $rule;
            if (!method_exists(Upload::class, $method)) {
                throw new ExceptionRule(\sprintf('Unknown Upload Rule [%s]', $method));
            }
            return $this->$method($value, $ruleOpts, $element);
        }
        return true;
    }

    /**
     * @param false|UploadedFileInterface $value
     */
    private function checkSystem($value, $message, Ruleable $element): bool
    {
        if ($value === false) {
            return true;
        }

        if (!in_array($value->getError(), array(\UPLOAD_ERR_OK, \UPLOAD_ERR_NO_FILE))) {
            $this->setMessage($this->getSystemMessage($value->getError()));
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param false|UploadedFileInterface $value
     */
    private function checkRequired($value, ?string $message, Ruleable $element): bool
    {
        if (is_null($message)) {
            $message = 'Выберите файл для загрузки';
        }
        $this->setMessage($message);

        if ($value === false || $value->getError() == \UPLOAD_ERR_NO_FILE) {
            $element->setRuleError($this->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param false|UploadedFileInterface $value
     */
    private function checkMaxsize($value, int|array|string $ruleOpts, Ruleable $element): bool
    {
        if ($value === false) {
            return true;
        }

        $parsed = $this->parseRuleOpts($ruleOpts);

        $threshold_size = $parsed['param'];
       // Assert::in($threshold_size);

        $message = $parsed['message'];

        if (is_null($message)) {
            $message = 'Размер файла (' . Binary::bytes($value->getSize() ?? 0)->format(null, " ") . ')'
                . ' превышает допустимый размер: ' . Binary::bytes($threshold_size)->format(null, " ");
        }
        $this->setMessage($message);

        if ($value->getSize() > $threshold_size) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }


    /**
     * @param false|UploadedFileInterface $value
     */
    private function checkExtensions($value, string|array $ruleOpts, Ruleable $element): bool
    {
        if ($value === false) {
            return true;
        }

        $parsed = $this->parseRuleOpts($ruleOpts);

        $expected_extensions = \array_map('trim', \explode(",", $parsed['param']));
        $message = $parsed['message'];

        $extension = pathinfo($value->getClientFilename() ?? '', PATHINFO_EXTENSION);

        if (is_null($message)) {
            $message = 'Загрузка файлов с расширением .' . $extension . ' запрещена';
        }
        $this->setMessage($message);

        if (!in_array($extension, $expected_extensions)) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }


    private function parseRuleOpts($opts): array
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
