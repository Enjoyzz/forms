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

use \PHPUnit\Framework\TestCase;
use \Enjoys\Forms\Form;

/**
 * Description of DefaultsTest
 *
 * @author deadl
 */
class DefaultsTest extends TestCase
{
    use \Tests\Enjoys\Forms\Reflection;

    /**
     *
     * @var  Enjoys\Forms\Forms $form 
     */
    protected $form;

    protected function setUp(): void
    {
        $this->form = new Form();
        $this->form->removeElement('_token_submit');
    }

    protected function tearDown(): void
    {
        $this->form = null;
    }
    
    public function test_render_header()
    {

 
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form->setAction('test'));
        $this->assertSame("<form action=\"test\"> </form>", $this->toOneString($obj->__toString()));
    }    

    public function test_render_hidden()
    {

        $this->form->hidden('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("<input type=\"hidden\" name=\"foo\" value=\"bar\" id=\"baz\">\n", $obj->hidden());
    }

    public function test_render_hidden2()
    {

        $this->form->hidden('foo', 'bar');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("<input type=\"hidden\" name=\"foo\" value=\"bar\">\n", $obj->hidden());
    }

    public function test_render_element_width_invalid_element()
    {


        $elements[] = 'invalid';
        $elements[] = new \Enjoys\Forms\Elements\Text(new \Enjoys\Forms\FormDefaults([]), 'bar');
        $elements[] = new \Enjoys\Forms\Elements\Password(new \Enjoys\Forms\FormDefaults([]), 'baz');


        $obj = new \Enjoys\Forms\Renderer\Defaults(new Form());
        $obj->setElements($elements);
        $obj->elements();
        $valideElementsCnt = $this->getPrivateProperty(\Enjoys\Forms\Renderer\Defaults::class, 'count_valid_element');
        $this->assertEquals("2", $valideElementsCnt->getValue($obj));
    }

    public function test_render_element_width_hidden_include_in_element_after_method_hidden()
    {

        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $obj->getForm()->addElement(new \Enjoys\Forms\Elements\Hidden(new \Enjoys\Forms\FormDefaults([]), 'foo'));
        $obj->getForm()->addElement(new \Enjoys\Forms\Elements\Submit(new \Enjoys\Forms\FormDefaults([]), 'bar'));
        $obj->setElements($obj->getForm()->getElements());

        $this->assertSame("\t<br><br><input type=\"submit\" id=\"bar\" name=\"bar\">\n", $obj->elements());
    }

    public function test_render_text()
    {
        $this->form->text('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("\t<label for=\"baz\">bar</label><br>\n\t<input type=\"text\" id=\"baz\" name=\"foo\"><br>\n\n", $obj->elements());
    }

    public function test_render_description()
    {
        $this->form->text('foo', 'bar')->setDescription('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame("\t<label for=\"foo\">bar</label><br>\n\t<input type=\"text\" id=\"foo\" name=\"foo\"><br>\n\t<small>baz</small><br>\n\n", $obj->elements());
    }

    public function test_render_submit()
    {
        $this->form->submit('foo', 'bar')->setId('baz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertSame(" <br><br><input type=\"submit\" id=\"baz\" name=\"foo\" value=\"bar\"> ", $this->toOneString($obj->elements()));
    }

    public function test_footer_with_closeheader()
    {
        $this->form->header('foo');
        $this->form->text('foo2');
        $this->form->text('foo3');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $obj->elements();
        $this->assertSame(" </fieldset> </form>", $this->toOneString($obj->footer()));
    }

    public function test_header_after1()
    {
        $this->form->header('foo')->closeAfter(1);
        $this->form->text('foo2');
        $this->form->text('foo3');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);

        $this->assertNotFalse(\stripos($this->toOneString($obj->elements()), "name=\"foo2\"><br> </fieldset>",));
    }

    public function test_two_header()
    {
        $this->form->header('hyt');
        $this->form->text('foo');
        $this->form->header('hyz');
        $this->form->text('bar');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertNotFalse(\stripos($this->toOneString($obj->elements()), "name=\"foo\"><br> </fieldset> <fieldset>",));
    }

    public function test_two_header_in_a_row()
    {
        $this->form->header('hyt');
        $this->form->header('hyz');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertNotFalse(\stripos($this->toOneString($obj->elements()), "</fieldset> <fieldset>",));
    }

    public function test_radio()
    {
        $this->form->radio('name', 'title')->addLabelAttributes('accesskey', 'x')->setPrefixId('rb_')->fill([
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

    public function test_checkbox()
    {

        $this->form->checkbox('name', 'title')->addLabelAttributes('accesskey', 'x')->setPrefixId('rb_')->fill([
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

    public function test_input_error_rule()
    {

        \Enjoys\Forms\Validator::check([
            $this->form->text('foo')->addRule('required', 'input_error')
        ]);
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertStringContainsString('<p style="color: red">input_error</p>', $obj->elements());
    }

    public function test_checkbox_error_rule()
    {

        \Enjoys\Forms\Validator::check([
            $this->form->checkbox('foot')->addRule('required', 'checkbox_error')
        ]);
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertStringContainsString('<p style="color: red">checkbox_error</p>', $obj->elements());
    }
    
    public function test_select_error_rule()
    {

        \Enjoys\Forms\Validator::check([
            $this->form->select('foot')->addRule('required', 'select_error')
        ]);
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        $this->assertStringContainsString('<p style="color: red">select_error</p>', $obj->elements());
    }

    public function test_rendererTextarea()
    {
        $this->form->textarea('foo', 'byz')->setValue('bar')->setDescription('zed');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertStringContainsString('<label for="foo">byz</label><br> <textarea id="foo" name="foo">bar</textarea><br> <small>zed</small><br> ', $this->toOneString($obj->elements()));
    }

    public function test_rendererDatalist()
    {
        $this->form->datalist('foo', 'byz')->fill(['bar', 'baz'])->setDescription('zed');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertStringContainsString('<label for="foo">byz</label><br> <input name="foo" list="foo"><datalist id="foo"> <option value="bar"> <option value="baz"> </datalist> <small>zed</small><br>', $this->toOneString($obj->elements()));
    }

    public function test_renderSelect()
    {
        $this->form->select('foo', 'byz')->fill(['bar', 'baz'])->setDescription('zed');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertStringContainsString('<label for="foo">byz</label><br> <select id="foo" name="foo"><br> <option id="0" value="0">bar</option><br> <option id="1" value="1">baz</option><br> </select> <small>zed</small><br>', $this->toOneString($obj->elements()));
    }

    public function test_renderButton()
    {
        $this->form->button('foo', '<b>test</b>')->setDescription('zed');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $this->assertStringContainsString('<button id="foo" name="foo"><b>test</b></button><br> <small>zed</small>', $this->toOneString($obj->elements()));
    }

    public function test_renderCaptcha_default()
    {
        $this->form->captcha()->setDescription('foo');
        $obj = new \Enjoys\Forms\Renderer\Defaults($this->form);
        //$this->assertSame("\t</fieldset>\n</form>", preg_replace ('/\s+/', ' ', $obj->elements()));
        $string = $this->toOneString($obj->elements());
        $this->assertStringContainsString('<input id="captcha_defaults" name="captcha_defaults" type="text" autocomplete="off">', $string);
        $this->assertStringContainsString('<label for="captcha_defaults"></label><br><br><img src="data:image/jpeg;base64', $string);
        $this->assertStringContainsString('<small>foo</small>', $string);
    }

    private function toOneString($multistring)
    {
        return preg_replace('/\s+/', ' ', $multistring);
    }

}
