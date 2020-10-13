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

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Form;
use PHPUnit\Framework\TestCase;

/**
 * Class CsrfTest
 *
 * @author Enjoys
 */
class CsrfTest extends TestCase
{

    public function test_disable_csrf()
    {
        $form = new Form('post');
        $this->assertTrue($form->getElements()[Form::_TOKEN_CSRF_] instanceof Hidden);
        $form->csrf(false);
        $this->assertNull($form->getElements()[Form::_TOKEN_CSRF_]);
    }

    public function test_disable_csrf_for_get()
    {
        $form = new Form();
        $form->csrf();
        $this->assertNull($form->getElements()[Form::_TOKEN_CSRF_]);
    }

}