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

use Enjoys\Forms\Forms;

/**
 * Description of HeaderTest
 *
 * @author deadl
 */
class HeaderTest extends \PHPUnit\Framework\TestCase
{

    private function getFormDefaults($data = [])
    {
        return new \Enjoys\Forms\FormDefaults($data, new \Enjoys\Forms\Form());
    }

    public function test_full_construct()
    {
        $obj = new \Enjoys\Forms\Elements\Header($this->getFormDefaults(), 'title');
        $this->assertSame('title', $obj->getTitle());
    }

    public function test_attr_legend()
    {
        $obj = new \Enjoys\Forms\Elements\Header($this->getFormDefaults(), 'title');
        $obj->addAttributes([
            'id' => 'test'
        ]);
        $this->assertSame('test', $obj->getAttribute('id'));
    }

    public function test_attr_fieldset()
    {
        $obj = new \Enjoys\Forms\Elements\Header($this->getFormDefaults(), 'title');
        $obj->addFieldsetAttribute([
            'id' => 'test'
        ]);
        $this->assertSame('test', $obj->getFieldsetAttribute('id'));
    }

    public function test_attr_fieldset_get()
    {
        $obj = new \Enjoys\Forms\Elements\Header($this->getFormDefaults(), 'title');
        $obj->addFieldsetAttribute([
            'id' => 'test',
            'disabled' => null
        ]);
        $this->assertSame(' id="test" disabled', $obj->getFieldsetAttributes());
    }
    
    public function test_close_after()
    {
        $obj = new \Enjoys\Forms\Elements\Header($this->getFormDefaults(), 'title');
        $obj->closeAfter(5);
        $this->assertSame(5, $obj->getCloseAfterCountElements());
    }    

}
