<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Enjoys\Forms\Rule;

use ByteUnits\Binary;
use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\RuleInterface;
use Enjoys\Forms\Rules;
use HttpSoft\Message\UploadedFile;

/**
 * Description of Upload
 * @author Enjoys
 */
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

    /**
     * 
     * @param int $error
     * @return string
     */
    private function getSystemMessage(int $error): string
    {
        if (isset($this->systemErrorMessage[$error])) {
            return $this->systemErrorMessage[$error];
        }
        return $this->systemErrorMessage['unknown'];
    }

    /**
     * 
     * @param Element $element
     * @return bool
     */
    public function validate(Element $element): bool
    {
        $value = \getValueByIndexPath($element->getName(), $this->getRequest()->files());

        if (false === $this->check($value, $element)) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @param mixed $value
     * @param Element $element
     * @return bool
     * @throws ExceptionRule
     */
    private function check($value, Element $element): bool
    {
        foreach ($this->getParams() as $rule => $ruleOpts) {
            // $method = 'unknown';
            if (is_int($rule) && is_string($ruleOpts)) {
                $rule = $ruleOpts;
                $ruleOpts = null;
            }
            $method = 'check' . \ucfirst($rule);
            if (!method_exists(Upload::class, $method)) {
                throw new ExceptionRule(\sprintf('Unknown Upload Rule [%s]', $method));
            }
            if (!$this->$method($value, $ruleOpts, $element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @psalm-suppress UndefinedMethod
     * @param UploadedFile|false   $value
     * @param mixed $message
     * @param Element $element
     * @return boolean
     */
    private function checkSystem($value, $message, Element $element)
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
     * @psalm-suppress UndefinedMethod
     * @param UploadedFile|false   $value
     * @param string $message
     * @param Element $element
     * @return boolean
     */
    private function checkRequired($value, ?string $message = null, Element $element)
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
     * @psalm-suppress UndefinedMethod
     * @param UploadedFile|false    $value
     * @param mixed $ruleOpts
     * @param Element $element
     * @return boolean
     */
    private function checkMaxsize($value, $ruleOpts, Element $element)
    {
        if ($value === false) {
            return true;
        }

        $parsed = $this->parseRuleOpts($ruleOpts);

        $threshold_size = (int) $parsed['param'];
        $message = $parsed['message'];

        if (is_null($message)) {
            $message = 'Размер файла (' . Binary::bytes($value->getSize())->format(null, " ") . ')'
                    . ' превышает допустимый размер: ' . Binary::bytes($threshold_size)->format(null, " ");
        }
        $this->setMessage((string) $message);

        if ($value->getSize() > $threshold_size) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @psalm-suppress UndefinedMethod
     * @param UploadedFile|false    $value
     * @param mixed $ruleOpts
     * @param Element $element
     * @return boolean
     */
    private function checkExtensions($value, $ruleOpts, Element $element)
    {
        if ($value === false) {
            return true;
        }

        $parsed = $this->parseRuleOpts($ruleOpts);

        $expected_extensions = \array_map('trim', \explode(",", $parsed['param']));
        $message = $parsed['message'];

        $extension = pathinfo($value->getClientFilename(), PATHINFO_EXTENSION);

        if (is_null($message)) {
            $message = 'Загрузка файлов с расширением .' . $extension . ' запрещена';
        }
        $this->setMessage((string) $message);

        if (!in_array($extension, $expected_extensions)) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param mixed $opts
     * @return array
     * @psalm-return array{param: mixed, message: mixed}
     */
    private function parseRuleOpts($opts): array
    {
        if (!is_array($opts)) {
            $opts = (array) $opts;
            $opts[1] = null;
        }
        list($param, $message) = $opts;

        return [
            'param' => $param,
            'message' => $message
        ];
    }
}
