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

namespace Tests\Enjoys\Forms;


use \PHPUnit\Framework\TestCase,
    Enjoys\Forms\Element,
    Enjoys\Forms\Form;
/**
 * Description of ElementTest
 *
 * @author deadl
 */
class ElementTest extends TestCase{
    /**
     *
     * @var  Enjoys\Forms\Forms $form 
     */
    protected $obj;

    protected function setUp(): void {
        $this->obj = new Form();
        $this->obj->removeElement(Form::_TOKEN_SUBMIT_);
        $this->obj->removeElement(Form::_TOKEN_CSRF_);
    }

    protected function tearDown(): void {
        $this->obj = null;
    }
    
    public function test_setDescription() {
        $this->obj->text('Foo', 'Bar')->setDescription('Zed');
        $elements = $this->obj->getElements();
        $first_key = \array_key_first($elements);
        $element =  $elements[$first_key];
        $this->assertSame('Zed',$element->getDescription());
    }
    
    public function test_setTitle() {
        $this->obj->text('Foo', 'Bar')->setTitle('Zed');
        $elements = $this->obj->getElements();
        $first_key = \array_key_first($elements);
        $element =  $elements[$first_key];
        $this->assertSame('Zed',$element->getTitle());
    }   
    
    public function test_setId() {
        $this->obj->text('Foo')->setId('Bar');
        $elements = $this->obj->getElements();
        $first_key = \array_key_first($elements);
        $element =  $elements[$first_key];
        $this->assertSame('Bar',$element->getId());
    }  
    
    public function test_getType() {
        $this->obj->text('Foo');
        $elements = $this->obj->getElements();
        $first_key = \array_key_first($elements);
        $element =  $elements[$first_key];
        $this->assertSame('text',$element->getType());
    }       
}
