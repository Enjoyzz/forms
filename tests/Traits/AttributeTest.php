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

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Forms;

/**
 * Description of AttributeTest
 *
 * @author deadl
 */
class AttributeTest extends \PHPUnit\Framework\TestCase {

    /**
     *
     * @var  Enjoys\Forms\Forms $obj 
     */
    protected $obj;

    protected function setUp(): void {
        $this->obj = new Forms();
    }

    protected function tearDown(): void {
        $this->obj = null;
    }

    public function testAddAttribute_arrays() {
        $this->obj->addAttribute(['first' => 'value1', 'second' => 'value2']);
        $this->assertSame('value1', $this->obj->getAttribute('first'));
        $this->assertSame('value2', $this->obj->getAttribute('second'));
    }

    public function testAddAttribute_lines() {
        $this->obj->addAttribute('first', 'value1');
        $this->assertSame('value1', $this->obj->getAttribute('first'));
    }

    public function testAddAttribute_class() {
        $this->obj->addAttribute('class', 'value1')->addAttribute('class', 'value2');
        $this->assertSame('value1 value2', $this->obj->getAttribute('class'));
    }

    public function testAddAttribute_not_class() {
        $this->obj->addAttribute('something', 'value1')->addAttribute('something', 'value2');
        $this->assertSame('value2', $this->obj->getAttribute('something'));
    }

    public function testGetAttributes() {
        $this->obj->addAttribute('something', 'value1')->addAttribute('something2');
        $this->assertSame(' something="value1" something2', $this->obj->getAttributes());
    }

    public function testGetAttributesArray() {
        $this->obj->addAttribute(['something' => 'value1', 'something2' => null]);
        $this->assertSame(' something="value1" something2', $this->obj->getAttributes());
    }

    public function testGetAttributesArray2() {
        $this->obj->addAttribute(['something' => 'value1', 'something2']);
        $this->assertSame(' something="value1" something2', $this->obj->getAttributes());
    }
    
    public function test_attr_without_value_get_attr() {
        $this->obj->addAttribute(['something' => 'value1', 'something2']);
        $this->assertNull($this->obj->getAttribute('something2'));
    }   
    
    public function test_get_attr_unseted() {
        $this->assertFalse($this->obj->getAttribute('something2'));
        $this->obj->setGroupAttributes('test');
        $this->assertFalse($this->obj->getAttribute('something2'));
        $this->obj->resetGroupAttributes();
    }        
    


}
