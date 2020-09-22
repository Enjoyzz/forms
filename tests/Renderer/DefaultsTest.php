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

        $this->assertSame("\t<br><input type=\"submit\" id=\"bar\" name=\"bar\">\n", $obj->elements());
    }

    public function test_render_text() {
        $this->form->text('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("\t<label for=\"baz\">bar</label><br>\n\t<input type=\"text\" id=\"baz\" name=\"foo\"><br>\n\n", $obj->elements());
    }
    
    public function test_render_description() {
        $this->form->text('foo', 'bar')->setDescription('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("\t<label for=\"foo\">bar</label><br>\n\t<input type=\"text\" id=\"foo\" name=\"foo\"><br>\n\t<small>baz</small><br>\n\n", $obj->elements());
    }    

    public function test_render_submit() {
        $this->form->submit('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame(" <br><input type=\"submit\" id=\"baz\" name=\"foo\"> ", $this->toOneString($obj->elements()));
    }

    public function test_footer_with_closeheader() {
        $this->form->header('foo');
        $this->form->text('foo2');
        $this->form->text('foo3');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $obj->elements();
        $this->assertSame(" </fieldset> </form>", $this->toOneString($obj->footer()));
    }

    public function test_header_after1() {
        $this->form->header('foo')->closeAfter(1);
        $this->form->text('foo2');
        $this->form->text('foo3');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);

        $this->assertNotFalse(\stripos($this->toOneString($obj->elements()), "name=\"foo2\"><br> </fieldset>",));
    }

    public function test_two_header() {
        $this->form->header('hyt');
        $this->form->text('foo');
        $this->form->header('hyz');
        $this->form->text('bar');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertNotFalse(\stripos($this->toOneString($obj->elements()), "name=\"foo\"><br> </fieldset> <fieldset>",));
    }

    public function test_two_header_in_a_row() {
        $this->form->header('hyt');
        $this->form->header('hyz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertNotFalse(\stripos($this->toOneString($obj->elements()), "</fieldset> <fieldset>",));
    }

    public function test_radio() {
        $this->form->radio('name', 'title')->addLabelAttribute('accesskey', 'x')->setPrefixId('rb_')->fill([
            'val1' => 'text1',
            'val3' => [
                'text3',
                [
                    'id' => 'id3',
                    'disabled'
                ]
            ],
            'val2' => 'text2'
            
        ])->setDescription('выберите 1 вариант');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("-", md5($this->toOneString($obj->elements())));
        $this->assertSame("96ddd3fd1770359fa1f28bc5eb28b8d3", md5($this->toOneString($obj->elements())));
    }

    public function test_checkbox() {
        $this->form->checkbox('name', 'title')->addLabelAttribute('accesskey', 'x')->setPrefixId('rb_')->fill([
            'val1' => 'text1',
            'val3' => [
                'text3',
                [
                    'id' => 'id3',
                    'disabled'
                ]
            ],
            'val2' => 'text2'
            
        ])->setDescription('выберите 1 вариант');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("-", $this->toOneString($obj->elements()));
        $this->assertSame("8d81691b697ccc5e78694363601e4525", md5($this->toOneString($obj->elements())));
    }
    
    private function toOneString($multistring) {
        return preg_replace('/\s+/', ' ', $multistring);
    }

}
