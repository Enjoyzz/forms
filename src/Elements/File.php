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

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;

/**
 * Description of File
 *
 * @author deadl
 */
class File extends Element
{

    /**
     *
     * @var string
     */
    protected string $type = 'file';

    public function __construct(Form $form, string $name, string $title = null)
    {
        parent::__construct($form, $name, $title);
        $this->addRule(Rules::UPLOAD, null, [
            'system'
        ]);

        $this->form->setAttribute('enctype', 'multipart/form-data');
        $this->form->setMethod('post');
        $this->setMaxFileSize(self::phpIniSize2bytes(ini_get('upload_max_filesize')), false);
    }

    /**
     *
     * @param string $ruleName
     * @param string $message
     * @param array $params
     * @return $this
     */
    public function addRule(string $ruleName, ?string $message = null, $params = [])
    {
        if (\strtolower($ruleName) !== \strtolower(Rules::UPLOAD)) {
            throw new ExceptionRule(
                    \sprintf("К элементу [%s] можно подключить только правило: [%s]", __CLASS__, Rules::UPLOAD)
            );
        }
        return parent::addRule($ruleName, $message, $params);
    }

    /**
     * 
     * @param int $bytes
     * @return $this
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->form->hidden('MAX_FILE_SIZE', (string) $bytes);
        return $this;
    }

//    
    //    /**
//     * 
//     * @todo перенести в другой проект
//     */
    static function phpIniSize2bytes($size_original): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size_original); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size_original); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return (int) round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return (int) round($size);
        }
    }
}
