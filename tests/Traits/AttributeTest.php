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

use Enjoys\Forms\Form;

/**
 * Description of AttributeTest
 *
 * @author deadl
 */
class AttributeTest 
{

    /**
     *
     * @var  Enjoys\Forms\Forms $obj 
     */
    protected $obj;

    protected function setUp(): void
    {
        $this->obj = new Form();
    }

    protected function tearDown(): void
    {
        $this->obj = null;
    }

    public function testAddAttribute_arrays()
    {
        $this->obj->setAttributes(['first' => 'value1', 'second' => 'value2']);
        $this->assertSame('value1', $this->obj->getAttribute('first'));
        $this->assertSame('value2', $this->obj->getAttribute('second'));
    }

    public function testAddAttribute_lines()
    {
        $this->obj->setAttribute('first', 'value1');
        $this->assertSame('value1', $this->obj->getAttribute('first'));
    }

    public function testAddAttribute_class()
    {
        //$this->markTestSkipped('test invalid! why? in project work all right!!');
        $this->obj->setAttribute('class', 'value1')->setAttribute('class', 'value2');
        $this->assertSame(['value1', 'value2'], $this->obj->getAttribute('class'));
    }

    public function testAddAttribute_not_class()
    {
        $this->obj->setAttribute('something', 'value1')->setAttribute('something', 'value2');
        $this->assertSame('value2', $this->obj->getAttribute('something'));
    }

    public function testGetAttributes()
    {
        $this->obj->setAttribute('something', 'value1')->setAttribute('something2');
        $this->assertSame(' something="value1" something2', $this->obj->getAttributes());
    }

    public function testGetAttributesArray()
    {
        $this->obj->setAttributes(['something' => 'value1', 'something2' => null]);
        $this->assertSame(' something="value1" something2', $this->obj->getAttributes());
    }

    public function testGetAttributesArray2()
    {
        $this->obj->setAttributes(['something' => 'value1', 'something2']);
        $this->assertSame(' something="value1" something2', $this->obj->getAttributes());
    }

    public function testGetAttributesClassMany()
    {
        $this->obj->setAttributes(['class' => 'value1'])->setAttributes(['class' => 'value2']);
        $this->assertStringContainsString('class="value1 value2"', $this->obj->getAttributes());
    }

    public function testGetAttributesClassMany2()
    {
        $this->obj->setAttributes(['class' => 'value1'])->setAttributes(['class' => 'value1']);
        $this->assertStringContainsString('class="value1"', $this->obj->getAttributes());
    }

    public function testGetAttributesClassMany3()
    {
        $this->obj
                ->setAttribute('class', 'value5')
                ->setAttribute('class', 'value5');
        $this->assertStringContainsString('class="value5"', $this->obj->getAttributes());
    }
    
    public function testGetAttributesClassMany4()
    {
        $this->obj->addClass('test1')->addClass('test2 test3');
        $this->assertEquals(['test1', 'test2', 'test3'], $this->obj->getAttribute('class'));
    }

    public function test_addClass_removeClass()
    {
        $this->obj->removeClass('not-isset-class');
        $this->assertEquals('', $this->obj->getAttributes());

        $this->obj
                ->addClass(5)
                ->addClass('test-class');
        $this->assertStringContainsString('class="5 test-class"', $this->obj->getAttributes());
        $this->obj->removeClass('test-class');
        $this->assertStringContainsString('class="5"', $this->obj->getAttributes());
        $this->obj->removeClass(5);
        $this->assertEquals('', $this->obj->getAttributes());
    }

    public function test_attr_without_value_get_attr()
    {
        $this->obj->setAttributes(['something' => 'value1', 'something2']);
        $this->assertNull($this->obj->getAttribute('something2'));
    }

    public function test_get_attr_unseted()
    {
        $this->obj->setAttribute('something2', true);
        $this->assertEquals(true, $this->obj->getAttribute('something2'));
        $this->assertEquals(false, $this->obj->getAttribute('something2', 'test'));
    }
    
   public function test_set_attr_where_value_is_array()
    {
        $this->obj->setAttributes([
            'class' => [
                'test',
                'test-2'
            ]
        ]);
        $this->assertEquals(['test', 'test-2'], $this->obj->getAttribute('class'));
        
        $this->obj->setAttributes([
            'something' => [
                'test',
                'test-2'
            ]
        ]);
        
        $this->assertEquals('test-2', $this->obj->getAttribute('something'));
    }    
}
