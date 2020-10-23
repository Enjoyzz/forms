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

namespace Tests\Enjoys\Forms\Elements;

/**
 * Description of FileTest
 *
 * @author deadl
 */
class FileTest extends \PHPUnit\Framework\TestCase {

    public function test_max_file_size() {
        $form = new \Enjoys\Forms\Form();
        $form->setMaxFileSize(25);
        $elements = $form->getElements()['MAX_FILE_SIZE'];
        $this->assertSame('25', $elements->getAttribute('value'));
    }

    public function test_max_file_size2() {
        $form = new \Enjoys\Forms\Form();
        $form->file(1, 1);
        $elements = $form->getElements()['MAX_FILE_SIZE'];
        $this->assertSame((string) \Enjoys\Forms\Form::phpIniSize2bytes(ini_get('upload_max_filesize')), $elements->getAttribute('value'));
    }

    public function test_max_file_size3() {
        $form = new \Enjoys\Forms\Form();
        $form->file(1, 1);
        $form->setMaxFileSize(25, true);
        $form->setMaxFileSize(150, false);
        $elements = $form->getElements()['MAX_FILE_SIZE'];
        $this->assertSame('25', $elements->getAttribute('value'));
        $form->setMaxFileSize(150, true);
        $elements = $form->getElements()['MAX_FILE_SIZE'];
        $this->assertSame('150', $elements->getAttribute('value'));
    }
    
    public function test_enctype_method() {
        $form = new \Enjoys\Forms\Form();
        $form->file(1, 1);
        $this->assertSame('POST', $form->getAttribute('method'));
        $this->assertSame('multipart/form-data', $form->getAttribute('enctype'));
    }    
    
    public function test_invalid_add_rule() {
        $this->expectException(\Enjoys\Forms\Exception\ExceptionRule::class);
        $form = new \Enjoys\Forms\Form();
        $form->file(1)->addRule(\Enjoys\Forms\Rules::REQUIRED);
    }        

}
