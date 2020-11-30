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

/**
 * Description of AttributeTest
 *
 * @author deadl
 */
class AttributeTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * @var  Enjoys\Forms\Forms $obj 
     */
//    protected $obj;
//
//    protected function setUp(): void
//    {
//        $this->obj = new Form();
//    }
//
//    protected function tearDown(): void
//    {
//        $this->obj = null;
//    }
    
    /**
     * @dataProvider data
     */
    public function test_attributes($attributes, $expect)
    {
        $trait = $this->getMockForTrait(\Enjoys\Forms\Traits\Attributes::class);
        $trait->setAttributes($attributes);
        $this->assertEquals($expect, $trait->getAttributesString());
    }
    
    public function data()
    {
        return [
          [['test'], ' test'], 
          [[54=>'test'], ' test'], 
          [[' 0'=>'test'], ' 0="test"'], 
          [['test', 'id'=>'test'], ' test id="test"'], 
        ];
    }

    public function testAddAttribute_arrays()
    {
        $trait = $this->getMockForTrait(\Enjoys\Forms\Traits\Attributes::class);
        $trait->setAttributes(['first' => 'value1', 'second' => 'value2']);
        $this->assertSame('value1', $trait->getAttribute('first'));
        $this->assertSame('value2', $trait->getAttribute('second'));

        $trait->setAttributes(['value_withoutkey', 'second' => 'value2']);
        $this->assertEquals(null, $trait->getAttribute('value_withoutkey'));

        $trait->addClass('class1');
        $this->assertEquals(['class1'], $trait->getAttribute('class'));

        $trait->addClass('class2 class3');
        $trait->addClass('class1');
        $this->assertEquals([
            'class1',
            'class2',
            'class3'
        ], $trait->getAttribute('class'));
        
        $trait->removeClass('class2');
        $this->assertEquals([
            'class1',
            'class3'
        ], array_values($trait->getAttribute('class')));

        
        $trait->setAttributes(['value_withoutkey', 'second' => 'value2'], 'extra');
        $trait->removeClass('class1', 'extra');
        
        $trait->addClass('class1', 'extra');
        $trait->removeClass('class1', 'extra');
        
        $this->assertEquals([], $trait->getAttribute('class', 'extra'));
        
        $this->assertEquals(null, $trait->getAttribute('value_withoutkey'));
        
        $this->assertStringContainsString('first="value1" second="value2" value_withoutkey class="class1 class3', $trait->getAttributesString());
        $this->assertStringContainsString('value_withoutkey second="value2"', $trait->getAttributesString('extra'));
        
        $this->assertEquals(false, $trait->getAttribute('value', 'extra2'));
        $this->assertEquals('', $trait->getAttributesString('extra3'));
        
        $trait->setAttributes([
            'first' => [
                'value1', 'value2'
            ], 
            'second' => 'value3'
        ]);
        $this->assertSame('value1 value2', $trait->getAttribute('first'));   
        
        $trait->setAttributes(['first' => 'value1'], 'extra4')->setAttributes(['first' => 'value2'], 'extra4');
        $this->assertSame('value2', $trait->getAttribute('first', 'extra4'));    
        
         $trait->setAttributes([
            'class' => [
                'value1', 'value2'
            ]
        ], 'extra5');
        $this->assertSame(['value1 value2'], $trait->getAttribute('class', 'extra5'));        
        
      
       
    }

    public function testAddAttribute_class()
    {
        $trait = $this->getMockForTrait(\Enjoys\Forms\Traits\Attributes::class);
        $trait->setAttribute('class', 'value1')->setAttribute('class', 'value2');
        $this->assertSame(['value1', 'value2'], $trait->getAttribute('class'));
        
        $trait->addClass([
            1, 2
        ]);
        
        $this->assertSame(['value1', 'value2', '1', '2'], $trait->getAttribute('class'));
    }


}
