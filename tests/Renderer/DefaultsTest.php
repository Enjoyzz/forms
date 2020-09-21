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

namespace Tests\Enjoys\Forms\Renderer;

use \PHPUnit\Framework\TestCase,
    Enjoys\Forms\Forms;

/**
 * Description of DefaultsTest
 *
 * @author deadl
 */
class DefaultsTest extends TestCase {

    /**
     *
     * @var  Enjoys\Forms\Forms $form 
     */
    protected $form;

    protected function setUp(): void {
        $this->form = new Forms();
    }

    protected function tearDown(): void {
        $this->form = null;
    }

    public function test_render_hidden() {

        $this->form->hidden('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("<input type=\"hidden\" id=\"baz\" name=\"foo\" value=\"bar\">\n", $obj->hidden());
    }

    public function test_render_element_width_invalid_element() {

        $form = $this->getMockBuilder(Forms::class)
                ->disableOriginalConstructor()
                ->getMock();

        $form->elements[] = 'invalid';
//        $form->elements[] = new \Enjoys\Forms\Elements\Hidden('foo');
//        $form->elements[] = new \Enjoys\Forms\Elements\Text('bar');
//        $form->elements[] = new \Enjoys\Forms\Elements\Password('baz');

        $form->expects($this->once())
                ->method('getElements')
                ->will($this->returnValue($form->elements));


//        $this->_getInnerPropertyValueByReflection($form);
        $obj = new \Enjoys\Forms\Renderer\Defaults($form);
        $this->assertSame("<form>\n</form>", $obj->__toString());
    }

    public function test_render_element_width_hidden_include_in_element_after_method_hidden() {

        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $obj->getForm()->addElement(new \Enjoys\Forms\Elements\Hidden('foo'));
        $obj->getForm()->addElement(new \Enjoys\Forms\Elements\Submit('bar'));
        $obj->setElements($obj->getForm()->getElements());
     
        $this->assertSame("\t<input type=\"submit\" id=\"bar\" name=\"bar\">\n", $obj->elements());
    }

    public function test_render_text() {
        $this->form->text('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("\t<label for=\"baz\">bar</label>\n\t<input type=\"text\" id=\"baz\" name=\"foo\">\n", $obj->elements());
    }

    public function test_render_submit() {
        $this->form->submit('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("\t<input type=\"submit\" id=\"baz\" name=\"foo\">\n", $obj->elements());
    }

}
