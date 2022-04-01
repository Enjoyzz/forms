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

use Enjoys\Forms\Elements\Datalist;
use PHPUnit\Framework\TestCase;

/**
 * Class DatalistTest
 *
 * @author Enjoys
 */
class DatalistTest extends TestCase
{

    public function test_init_datalist()
    {
 
        $el = new Datalist('foo', 'title1');
//        $this->assertIn($el instanceof Datalist);
        $this->assertNull($el->getAttr('id')?->getValueString());
        $this->assertSame('foo', $el->getAttr('list')->getValueString());
    }
    
    public function test_basehtml()
    {
        $el = new Datalist('foo', 'title1');
        $this->assertEquals('', $el->baseHtml());
    }
}
