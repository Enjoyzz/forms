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

use Enjoys\Forms\Form

;

/**
 * Description of FormsTest
 *
 * @author deadl
 */
class FormTest extends \PHPUnit\Framework\TestCase
{

    use Reflection;
    


    public function test_init_form_1_0()
    {
        $form = new Form();
        $this->assertEquals('GET', $form->getMethod());
    }

    public function data_init_form_1_1()
    {
        return [
            ['get', 'GET'],
            ['Get', 'GET'],
            ['GeT', 'GET'],
            ['post', 'POST'],
            ['pOSt', 'POST'],
            ['something', 'GET']
        ];
    }

    /**
     * 
     * @dataProvider data_init_form_1_1
     */
    public function test_init_form_1_1($method, $expected)
    {
        $form = new \Enjoys\Forms\Form([
            'method' => $method
        ]);
        $this->assertEquals($expected, $form->getMethod());
    }

    public function test_setName_1_0()
    {
        $form = new Form();
        $form->setOption('name', 'test_form');
        $this->assertEquals('test_form', $form->getOption('name'));
        $this->assertEquals('test_form', $form->getAttribute('name'));
        $form->setOption('name', null);
        $this->assertEquals(null, $form->getOption('name'));
        $this->assertEquals(false, $form->getAttribute('name'));
    }

    public function test_setAction_1_0()
    {
        $form = new Form([
            'method' => 'post',
            'action' => 'test.php',
        ]);
        $this->assertEquals('test.php', $form->getOption('action'));
        $this->assertEquals('test.php', $form->getAttribute('action'));
        $form->setOption('action', 'foo.php');
        $this->assertEquals('foo.php', $form->getOption('action'));
        $this->assertEquals('foo.php', $form->getAttribute('action'));
        $form->setOption('action', null);
        $this->assertEquals(null, $form->getOption('action'));
        $this->assertEquals(false, $form->getAttribute('action'));
    }

    public function test_Renderer_1_0()
    {
        $this->markTestSkipped('переделать тест');
        $form = new Form();
        $form->setRenderer('defaults');
        $this->assertEquals(true, $form->display() instanceof \Enjoys\Forms\Renderer\Defaults\Defaults);
    }

    public function test_Renderer_1_1()
    {
         $this->markTestSkipped('переделать тест');
        $this->expectException(\Enjoys\Forms\Exception\ExceptionRenderer::class);
        $form = new Form();
        $form->setRenderer('invalid');
        $form->display();
        //  $this->assertEquals(true, $form->display() instanceof \Enjoys\Forms\Renderer\Defaults);
    }

    public function test_Call_1_0()
    {
        $form = new Form();
        $element = $form->text('foo');
        $this->assertEquals(true, $element instanceof \Enjoys\Forms\Element);
    }

    /**
     * @expectedException \Enjoys\Forms\Exception\ExceptionElement
     */
    public function test_Call_1_1()
    {
        $this->expectException(\Enjoys\Forms\Exception\ExceptionElement::class);
        $form = new Form();
        $form->invalid();
        //  $this->assertEquals(true, $form->display() instanceof \Enjoys\Forms\Renderer\Defaults);
    }

    public function test_addElement_1_0()
    {
        $this->markTestIncomplete();
        $form = new Form();
        $element = new \Enjoys\Forms\Elements\Text('foo');
        $method = $this->getPrivateMethod(Form::class, 'addElement');
        $method->invokeArgs($form, [$element]);
        $this->assertEquals(true, $form->getElements()['foo'] instanceof \Enjoys\Forms\Elements\Text);
    }

    /**
     * @expectedException \Enjoys\Forms\Exception\ExceptionElement
     */
    public function test_addElement_1_1()
    {
        $this->markTestIncomplete();
        $this->expectException(\Enjoys\Forms\Exception\ExceptionElement::class);
        $form = new Form();
        $form->text('foo');
        $element = new \Enjoys\Forms\Elements\Text('foo');
        $form->addElement($element);
    }

    public function test_addElement_1_2()
    {
        $this->markTestIncomplete();
        $form = new Form();
        $form->text('foo');
        $element = new \Enjoys\Forms\Elements\Text(new \Enjoys\Forms\Form([]), 'foo');
        $method = $this->getPrivateMethod(Form::class, 'addElement');
        $method->invokeArgs($form, [$element, true]);
        $this->assertEquals(true, $form->getElements()['foo'] instanceof \Enjoys\Forms\Elements\Text);
    }

    public function test_removeElement_1_0()
    {
        $this->markTestIncomplete();
        $form = new Form();
        $form->text('foo');
        $this->assertEquals(true, isset($form->getElements()['foo']));
        $form->removeElement('foo');
        $this->assertEquals(true, !isset($form->getElements()['foo']));
    }

    public function test_getFormDefaults_1_0()
    {
        $form = new Form();
        $form->setOption('defaults', ['foo' => 'bar']);
        $this->assertEquals(true, $form->getDefaultsHandler() instanceof \Enjoys\Forms\DefaultsHandler);
    }

    public function test_removeCsrf()
    {
        $this->markTestIncomplete();
        $form = new Form();
        $method = $this->getPrivateMethod(Form::class, 'csrf');
        $method->invoke($form);
        $this->assertEquals(true, !isset($form->getElements()[Form::_TOKEN_CSRF_]));
    }

    public function test_initCsrf()
    {
        $form = new Form([
            'method' => 'post'
        ]);
        $this->assertEquals(true, isset($form->getElements()[Form::_TOKEN_CSRF_]));
    }

    public function test_checkSubmittedFrom_1_0()
    {
        $this->markTestIncomplete('возможно этот тест надо убрать');
        $form = new Form([], new \Enjoys\Forms\Http\Request([
                    Form::_TOKEN_SUBMIT_ => 'b73c0491b1e2325c400e9375606ae2c3',
                    'foo' => 'baz'
        ]));
        $method = $this->getPrivateMethod(Form::class, 'checkFormSubmitted');
        $method->invoke($form);
        $this->assertEquals(true, $form->isSubmitted());
    }

    public function test_checkSubmittedFrom_1_1()
    {
        $this->markTestIncomplete('возможно этот тест надо убрать');
        $form = new Form([], new \Enjoys\Forms\Http\Request([
                    Form::_TOKEN_SUBMIT_ => 'invalid',
                    'foo' => 'baz'
        ]));
        $method = $this->getPrivateMethod(Form::class, 'checkFormSubmitted');
        $method->invoke($form);
        $this->assertEquals(false, $form->isSubmitted());
    }

    public function test_validate_1_0()
    {
        $this->markTestIncomplete();
        $form = new Form();
        $this->assertEquals(false, $form->validate());
    }

    public function test_validate_1_1()
    {
        $this->markTestIncomplete('возможно этот тест надо убрать');
        $form = new Form([], new \Enjoys\Forms\Http\Request([
                    Form::_TOKEN_SUBMIT_ => 'b73c0491b1e2325c400e9375606ae2c3',
                    'foo' => 'baz'
        ]));
        $method = $this->getPrivateMethod(Form::class, 'checkFormSubmitted');
        $method->invoke($form);

        $this->assertEquals(true, $form->validate());
    }

    public function test_init_file_1_0()
    {
        $form = new Form([
            'method' => 'get'
        ]);
        $form->file('myfile');
        $this->assertEquals('POST', $form->getMethod());
        $this->assertEquals('multipart/form-data', $form->getAttribute('enctype'));
        $method = $this->getPrivateMethod(Form::class, 'elementExists');
        $this->assertEquals(true, $method->invokeArgs($form, ['MAX_FILE_SIZE']));
    }

    public function test_setMaxFileSize()
    {
        $form = new Form();
        $form->file('file')->setMaxFileSize('10000');
        $this->assertEquals('10000', $form->getElements()['MAX_FILE_SIZE']->getAttribute('value'));
    }

    /**
     * 
     * @dataProvider dataForConstruct
     */
    public function testConstruct($method, $action, $expectedMethod, $expectedAction)
    {
        $this->markTestIncomplete('проверить тест');
        $this->form = new Form([
            'action' => $action,
            'method' => $method
        ]);
        $this->assertSame($expectedMethod, $this->form->getAttribute('method'));
        $this->assertSame($expectedAction, $this->form->getAttribute('action'));
    }

    public function dataForConstruct()
    {
        return [
            ['get', '/action.php', 'GET', '/action.php'],
            ['get', null, 'GET', false],
            [null, null, false, false],
            ['Post', null, 'POST', false],
        ];
    }

//    /**
//     * 
//     * @dataProvider dataCallValid
//     */
//    public function test_text_call($call) {
//        //$this->markTestSkipped('Чушь какая-то Tests\Enjoys\Forms\Renderer\DefaultsTest::test_checkbox()');
//        //$this->expectException(\Enjoys\Forms\Exception::class);
//        $result = $this->form->$call('test', 'testname');
//
//        $this->assertInstanceOf('\Enjoys\Forms\Element', $result);
//    }
//
//    public function dataCallValid() {
//        return [
//            ['text'], ['hidden'], ['password'], ['submit'], ['checkbox'],
////            ['button'], ['file'], ['image'], ['radio'], ['reset'], ['color'],
////            ['date'], ['datetime'], ['datetime-local'], ['email'], ['number'],
////            ['range'], ['search'], ['tel'], ['time'], ['url'], ['month'], ['week']
//        ];
//    }
//
//    public function test_double_set_elements() {
//        $this->expectException(\Enjoys\Forms\Exception::class);
//        $this->form->text('foo');
//        $this->form->text('foo');
//    }
//
//    public function test_invalid_display() {
//        $this->expectException(\Enjoys\Forms\Exception::class);
//        $this->form->setRenderer('invalid')->display();
//    }
//
//    public function test_valid_display() {
//
//        $result = $this->form->setRenderer('defaults')->display();
//        $this->assertTrue($result instanceof \Enjoys\Forms\Renderer\Defaults);
//    }
//
    public function test_count_elements()
    {
        $form = new Form();
        $form->text('foo');
        $form->hidden('bar');
        $form->password('baz');
        //+1 submit_tokene element
        $this->assertCount(4, $form->getElements());
    }

//
    public function test_remove_elements()
    {
        $this->markTestIncomplete();
        $form = new Form();
        $form->text('foo');
        $form->hidden('bar');
        $form->removeElement(Form::_TOKEN_SUBMIT_);
        $form->removeElement('foo');
        $this->assertCount(1, $form->getElements());
    }

    public function test_setDefaults_1_0()
    {
        $form = new Form();
        $form->setOption('defaults', [
            'foo' => 'bar'
        ]);
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttribute('value'));
    }

    public function test_setDefaults_1_1()
    {
        $form = new Form();
        $form->setOption('defaults', [
            'foo' => 'bar'
        ]);
        $element = $form->text('foo')->setName('baz');
        $this->assertSame('bar', $element->getAttribute('value'));
    }

////    public function test_set_isSubmit() {
////
////        $form = $this->getMockBuilder(Forms::class)
////                ->addMethods(['isSubmited'])
////                ->getMock();
////
////        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
////        unset($_GET);
////        $_GET['foo'] = 'baz';
////        $form->setFormDefaults(new \Enjoys\Forms\FormDefaults([
////            'foo' => 'bar'
////        ], $form));
////
////        $element = $form->text('foo');
////
////        $this->assertSame('baz', $element->getAttribute('value'));
////    }
////
////    public function test_set_default3_0() {
////        $form = $this->getMockBuilder(Forms::class)
////                ->addMethods(['isSubmited'])
////                ->getMock();
////
////
////
////        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
////
////        $_GET['zed'] = 'anystring';
////
////        $form->setFormDefaults(new \Enjoys\Forms\FormDefaults([
////            'foo' => 'bar'
////        ], $form));
////
////        $element = $form->text('foo')->setName('zed');
////
////        $this->assertSame('anystring', $element->getAttribute('value'));
////    }
////
////    public function test_set_default3_1() {
////        $form = $this->getMockBuilder(Forms::class)
////                ->setMethods(['isSubmited'])
////                ->getMock();
////
////        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
////
////
////        $_GET['zed'] = 'anystring';
////
////        $form->setDefaults([
////            'foo' => 'bar'
////        ]);
////
////        $element = $form->text('foo')->addAttributes([
////            'name' => 'zed',
////        ]);
////
////        $this->assertSame('anystring', $element->getAttribute('value'));
////    }
////
////    public function test_set_default5() {
////        $_GET['foo'] = 'bar';
////        $form = $this->getMockBuilder(Forms::class)
////                ->setMethods(['isSubmited'])
////                ->getMock();
////
////        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
////
////        $form->setDefaults([]);
////        $element = $form->text('foo');
////        $this->assertSame('bar', $element->getAttribute('value'));
////    }
////
////    public function test_set_default6() {
////        $_POST['foo'] = 'barx';
////        $form = $this->getMockBuilder(Forms::class)
////                ->setMethods(['isSubmited'])
////                ->getMock();
////        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
////
////        $form->setMethod('post');
////
////
////
////        $element = $form->text('foo');
////        $this->assertSame('barx', $element->getAttribute('value'));
////    }
//
//    public function test_set_default7_array() {
//        //$this->markTestSkipped('не доработан фунционал');
//        $_POST['foo']['var1'] = 'barx';
//        $_POST['foo']['var2'] = 'bzzz';
//        $_POST['foo'][0] = 'qwerty';
//        $_POST['foo']['var3'] = '12345';
//        $_POST['bar'] = 'rrrrr';
//        $_POST['xyz'][0] = 'invalid';
//        $form = $this->getMockBuilder(Form::class)
//                ->setMethods(['isSubmited'])
//                ->getMock();
//        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
//
//       // $form->setMethod('post');
//
//        $element1 = $form->text('foo[var1]');
//        $element2 = $form->text('foo[var2]');
//        $element3 = $form->text('foo[var3]');
//        $element4 = $form->text('foo[]');
//        $element5 = $form->text('bar');
//        $element6 = $form->text('xyz');
//
//        $this->assertSame('barx', $element1->getAttribute('value'));
//        $this->assertSame('bzzz', $element2->getAttribute('value'));
//        $this->assertSame('12345', $element3->getAttribute('value'));
//        $this->assertSame('qwerty', $element4->getAttribute('value'));
//        $this->assertSame('rrrrr', $element5->getAttribute('value'));
//        $this->assertFalse($element6->getAttribute('value'));
//    }

    public function test_validate_false()
    {
        $form = $this->getMockBuilder(Form::class)
                ->setMethods(['isSubmitted'])
                ->getMock();
        $form->expects($this->once())->method('isSubmitted')->will($this->returnValue(false));
        $this->assertFalse($form->validate());
    }

    public function test_validate_false_after_submit()
    {

        $form = $this->getMockBuilder(Form::class)
                ->setMethods(['isSubmitted'])
                ->getMock();

        $form->expects($this->once())->method('isSubmitted')->will($this->returnValue(true));

        $form->text('foo')->addRule('required');
        $this->assertFalse($form->validate());
    }

    public function test_validate_true()
    {
        $form = $this->getMockBuilder(Form::class)
                ->setMethods(['isSubmitted'])
                ->getMock();
        $form->expects($this->any())->method('isSubmitted')->will($this->returnValue(true));
        $this->assertTrue($form->validate());
    }

    public function test_formCount_1_0()
    {
        $form1 = new Form();
        $this->assertSame(1, $form1->getFormCount());
        unset($form1);
    }

    public function test_formCount_1_1()
    {
        $form1 = new Form();
        $this->assertNotSame(2, $form1->getFormCount());
        unset($form1);
    }

    public function test_formCount_2_0()
    {
        $form1 = new Form();
        $form2 = new Form();
        $this->assertSame(1, $form1->getFormCount());
        $this->assertSame(2, $form2->getFormCount());
    }

    public function test_formCount_2_1()
    {
        $form1 = new Form();
        $form2 = new Form();
        $this->assertNotSame(2, $form1->getFormCount());
        $this->assertNotSame(1, $form2->getFormCount());
    }

    public function test_setDefaults_1_2()
    {
        $form1 = new Form();
        $form1->setOption('defaults', ['foo' => 'bar']);
        $form2 = new Form();
        $this->assertEquals(['foo' => 'bar'], $form1->getDefaultsHandler()->getDefaults());
        $this->assertEquals([], $form2->getDefaultsHandler()->getDefaults());
    }

}
