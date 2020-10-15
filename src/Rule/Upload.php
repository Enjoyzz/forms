<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
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

use Enjoys\Forms\Element;
use Enjoys\Forms\Forms;
use Enjoys\Forms\Interfaces\Rule;
use Enjoys\Forms\Rules;
use Enjoys\Helpers\Arrays;

/**
 * Description of Upload
 * @author Enjoys
 */
class Upload extends Rules implements Rule
{

    private $systemErrorMessage = [
        'unknown' => "Unknown upload error",
        \UPLOAD_ERR_INI_SIZE => "Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini.",
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

    public function validate(Element $element): bool
    {

// dump($this->request->files->get($element->getName()));
//$method = $this->request->get();
        $value = $this->request::getValueByIndexPath($element->getName(), $_FILES);


        if (false === $this->check($value, $element)) {

            return false;
        }

        return true;
    }

    private function check($value, Element $element)
    {
        foreach ($this->getParams() as $rule => $ruleOpts) {
            $method = 'unknown';

            if (is_int($rule) && is_string($ruleOpts)) {
                $rule = $ruleOpts;
                $ruleOpts = null;
            }

            $method = 'check' . \ucfirst($rule);
            if (!method_exists(Upload::class, $method)) {
                throw new \Enjoys\Forms\Exception\ExceptionRule(\sprintf('Unknown Upload Rule [%s]', 'check' . \ucfirst($rule)));
            }
            if (!$this->$method($value, $ruleOpts, $element)) {
                return false;
            }
        }
        return true;
    }

    private function checkSystem($value, $message, Element $element)
    {
        if (!in_array($value['error'], array(\UPLOAD_ERR_OK, \UPLOAD_ERR_NO_FILE))) {
            $message = $this->systemErrorMessage['unknown'];
            if (isset($this->systemErrorMessage[$value['error']])) {
                $message = $this->systemErrorMessage[$value['error']];
            }
            $this->setMessage($message);

            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    private function checkRequired($value, $message, Element $element)
    {

        if (is_null($message)) {
            $message = 'Выберите файл для загрузки';
        }
        $this->setMessage($message);
        if ($value['error'] == \UPLOAD_ERR_NO_FILE) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }

    private function checkMaxsize($value, $params, Element $element)
    {

//dump($value);
//dump($params);

        $threshold_size = $params;
        $message = null;

        if (is_null($message)) {
            $message = 'Размер файла превышает допустимый размер';
        }
        $this->setMessage($message);
        if (
                $value['error'] == \UPLOAD_ERR_FORM_SIZE ||
                $value['size'] > $threshold_size
        ) {
            $element->setRuleError($this->getMessage());
            return false;
        }
        return true;
    }
}
