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

namespace Tests\Enjoys\Forms\Renderer\Providers\Bs4;

use \PHPUnit\Framework\TestCase,
    Enjoys\Forms\Forms;

/**
 * Description of Bs4Test
 *
 * @author deadl
 */
class Bs4Test 
{

    /**
     *
     * @var  Enjoys\Forms\Forms $form 
     */
    protected $form;

    protected function setUp(): void
    {
        $this->form = new Forms();
    }

    protected function tearDown(): void
    {
        $this->form = null;
    }

    public function test_bs4class()
    {

        //$this->form->hidden('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Bs4($this->form);
        $this->assertTrue($obj instanceof \Enjoys\Forms\Renderer\Bs4);
    }

    public function test_tostring()
    {

        //$this->form->hidden('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Bs4($this->form);
        $this->assertSame('', $obj->__toString());
    }

}
