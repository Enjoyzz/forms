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

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Traits;

use function iniSize2bytes;

/**
 * Description of File
 *
 * @author Enjoys
 */
class File extends Element
{
    use Traits\Description;
    use Traits\Rules {
        addRule as private parentAddRule;
    }

    /**
     *
     * @var string
     */
    protected ?string $type = 'file';

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->addRule(Rules::UPLOAD, null, [
            'system'
        ]);
    }

    public function prepare()
    {
        $this->getForm()->setAttribute('enctype', 'multipart/form-data');
        $this->getForm()->setMethod('post');
        $this->setMaxFileSize(iniSize2bytes(ini_get('upload_max_filesize')), false);
    }

    /**
     *
     * @param int $bytes
     * @return $this
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->getForm()->hidden('MAX_FILE_SIZE', (string) $bytes);
        return $this;
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
        return $this->parentAddRule($ruleName, $message, $params);
    }
}
