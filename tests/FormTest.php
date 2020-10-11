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

    public function test_init_form_1_0() {
        $form = new Form();
        $this->assertEquals('GET', $form->getMethod());
    }

    public function data_init_form_1_1() {
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
    public function test_init_form_1_1($method, $expected) {
        $form = new \Enjoys\Forms\Form($method);
        $this->assertEquals($expected, $form->getMethod());
    }

    public function test_setName_1_0() {
        $form = new Form();
        $form->setName('test_form');
        $this->assertEquals('test_form', $form->getName());
        $this->assertEquals('test_form', $form->getAttribute('name'));
        $form->setName();
        $this->assertEquals(null, $form->getName());
        $this->assertEquals(false, $form->getAttribute('name'));
    }

    public function test_setAction_1_0() {
        $form = new Form('post', 'test.php');
        $this->assertEquals('test.php', $form->getAction());
        $this->assertEquals('test.php', $form->getAttribute('action'));
        $form->setAction('foo.php');
        $this->assertEquals('foo.php', $form->getAction());
        $this->assertEquals('foo.php', $form->getAttribute('action'));
        $form->setAction();
        $this->assertEquals(null, $form->getAction());
        $this->assertEquals(false, $form->getAttribute('action'));
    }

    public function test_Renderer_1_0() {
        $form = new Form();
        $form->setRenderer('defaults');
        $this->assertEquals(true, $form->display() instanceof \Enjoys\Forms\Renderer\Defaults);
    }

    public function test_Renderer_1_1() {
        $this->expectException(\Enjoys\Forms\Exception\ExceptionRenderer::class);
        $form = new Form();
        $form->setRenderer('invalid');
        $form->display();
        //  $this->assertEquals(true, $form->display() instanceof \Enjoys\Forms\Renderer\Defaults);
    }

    public function test_Call_1_0() {
        $form = new Form();
        $element = $form->text('foo');
        $this->assertEquals(true, $element instanceof \Enjoys\Forms\Element);
    }

    /**
     * @expectedException \Enjoys\Forms\Exception\ExceptionElement
     */
    public function test_Call_1_1() {
        $this->expectException(\Enjoys\Forms\Exception\ExceptionElement::class);
        $form = new Form();
        $form->invalid();
        //  $this->assertEquals(true, $form->display() instanceof \Enjoys\Forms\Renderer\Defaults);
    }

    public function test_addElement_1_0() {
        $form = new Form();
        $element = new \Enjoys\Forms\Elements\Text(new \Enjoys\Forms\FormDefaults([], $form), 'foo');
        $form->addElement($element);
        $this->assertEquals(true, $form->getElements()['foo'] instanceof \Enjoys\Forms\Elements\Text);
    }

    /**
     * @expectedException \Enjoys\Forms\Exception\ExceptionElement
     */
    public function test_addElement_1_1() {
        $this->expectException(\Enjoys\Forms\Exception\ExceptionElement::class);
        $form = new Form();
        $form->text('foo');
        $element = new \Enjoys\Forms\Elements\Text(new \Enjoys\Forms\FormDefaults([], $form), 'foo');
        $form->addElement($element);
    }

    public function test_addElement_1_2() {
        $form = new Form();
        $form->text('foo');
        $element = new \Enjoys\Forms\Elements\Text(new \Enjoys\Forms\FormDefaults([], $form), 'foo');
        $form->addElement($element, true);
        $this->assertEquals(true, $form->getElements()['foo'] instanceof \Enjoys\Forms\Elements\Text);
    }

    public function test_removeElement_1_0() {
        $form = new Form();
        $form->text('foo');
        $this->assertEquals(true, isset($form->getElements()['foo']));
        $form->removeElement('foo');
        $this->assertEquals(true, !isset($form->getElements()['foo']));
    }

    public function test_getFormDefaults_1_0() {
        $form = new Form();
        $form->setFormDefaults(new \Enjoys\Forms\FormDefaults([
                    'foo' => 'bar'
                        ], $form));
        $this->assertEquals(true, $form->getFormDefaults() instanceof \Enjoys\Forms\FormDefaults);
    }

    public function test_removeCsrf() {
        $form = new Form();
        $form->csrf();
        $this->assertEquals(true, !isset($form->getElements()[Form::_TOKEN_CSRF_]));
    }

    public function test_initCsrf() {
        $form = new Form('post');
        $this->assertEquals(true, isset($form->getElements()[Form::_TOKEN_CSRF_]));
    }

    public function test_checkSubmittedFrom_1_0() {
        $request = new \Enjoys\Forms\Http\Request([
            Form::_TOKEN_SUBMIT_ => '2fd7d78a07c754c821de3f00b96a19b1',
            'foo' => 'baz'
        ]);


        $form = new Form();
        $method = $this->getPrivateMethod(Form::class, 'checkSubmittedFrom');
        $method->invokeArgs($form, [
            $request
        ]);

        //$this->assertEquals('', $form->getElements()[Form::_TOKEN_SUBMIT_]->getAttribute('value'));
        $this->assertEquals(true, $form->isSubmited());
    }

    public function test_checkSubmittedFrom_1_1() {
        $request = new \Enjoys\Forms\Http\Request([
            Form::_TOKEN_SUBMIT_ => 'invalid',
            'foo' => 'baz'
        ]);


        $form = new Form();
        $method = $this->getPrivateMethod(Form::class, 'checkSubmittedFrom');
        $method->invokeArgs($form, [
            $request
        ]);

        //$this->assertEquals('', $form->getElements()[Form::_TOKEN_SUBMIT_]->getAttribute('value'));
        $this->assertEquals(false, $form->isSubmited());
    }

    public function test_validate_1_0() {
        $form = new Form();
        $this->assertEquals(false, $form->validate());
    }

    public function test_validate_1_1() {
        $request = new \Enjoys\Forms\Http\Request([
            Form::_TOKEN_SUBMIT_ => '2fd7d78a07c754c821de3f00b96a19b1',
            'foo' => 'baz'
        ]);


        $form = new Form();
        $method = $this->getPrivateMethod(Form::class, 'checkSubmittedFrom');
        $method->invokeArgs($form, [
            $request
        ]);
        $this->assertEquals(true, $form->validate());
    }
    
    public function test_init_file_1_0() {
        $form = new Form('get');
        $form->file('myfile');
        $this->assertEquals('POST', $form->getMethod());
        $this->assertEquals('multipart/form-data', $form->getAttribute('enctype'));
        $method = $this->getPrivateMethod(Form::class, 'elementExists');
        $this->assertEquals(true, $method->invokeArgs($form, ['MAX_FILE_SIZE']));
    }  
    
    public function test_setMaxFileSize() {
        $form = new Form();
        $form->setMaxFileSize('10000');
        $this->assertEquals('10000', $form->getElements()['MAX_FILE_SIZE']->getAttribute('value'));
    }
//
//    public function testGetElements() {
//
//        $this->assertIsArray($this->form->getElements());
//    }
//
//    public function testSetAction() {
//        $this->assertSame('/action.php', $this->form->setAction('/action.php')->getAttribute('action'));
//    }
//
//
//    /**
//     * 
//     * @dataProvider dataForConstruct
//     */
//    public function testConstruct($method, $action, $expectedMethod, $expectedAction) {
//        $this->form = new Form($method, $action);
//        $this->assertSame($expectedMethod, $this->form->getAttribute('method'));
//        $this->assertSame($expectedAction, $this->form->getAttribute('action'));
//    }
//
//    public function dataForConstruct() {
//        return [
//            ['get', '/action.php', 'GET', '/action.php'],
//            ['get', null, 'GET', false],
//            [null, null, false, false],
//            ['Post', null, 'POST', false],
//        ];
//    }
//
//    /**
//     * 
//     * @dataProvider dataProviderSetName
//     */
//    public function testSetName($name, $expected) {
//
//        $result = $this->form->setName($name);
//        $this->assertSame($expected, $this->form->getAttribute('name'));
//        $this->assertIsObject($result);
//        $this->assertTrue($result->getName() === null || is_string($result->getName()));
//    }
//
//    public function dataProviderSetName() {
//        return [
//            ['formname', 'formname'],
//            [null, false]
//        ];
//    }
//
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
//    public function test_invalid_call() {
//        $this->expectException(\Enjoys\Forms\Exception::class);
//        $this->form->invalid();
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
//    public function test_count_elements() {
//        $this->form->text('foo');
//        $this->form->hidden('bar');
//        $this->form->password('baz');
//        $this->assertCount(3, $this->form->getElements());
//    }
//
//    public function test_remove_elements() {
//        $this->form->text('foo');
//        $this->form->hidden('bar');
//        $this->form->removeElement('foo');
//        $this->assertCount(1, $this->form->getElements());
//    }
//
//    public function test_set_default() {
//        $this->form->setFormDefaults(new \Enjoys\Forms\FormDefaults([
//                    'foo' => 'bar'
//        ], $this->form));
//        $element = $this->form->text('foo');
//        $this->assertSame('bar', $element->getAttribute('value'));
//    }
//
//    public function test_set_default2() {
//         $this->form->setFormDefaults(new \Enjoys\Forms\FormDefaults([
//            'foo' => 'bar'
//        ], $this->form));
//        $element = $this->form->text('foo')->setName('baz');
//        $this->assertSame('bar', $element->getAttribute('value'));
//    }
//
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
////    public function test_set_default7_array() {
////        //$this->markTestSkipped('не доработан фунционал');
////        $_POST['foo']['var1'] = 'barx';
////        $_POST['foo']['var2'] = 'bzzz';
////        $_POST['foo'][0] = 'qwerty';
////        $_POST['foo']['var3'] = '12345';
////        $_POST['bar'] = 'rrrrr';
////        $_POST['xyz'][0] = 'invalid';
////        $form = $this->getMockBuilder(Forms::class)
////                ->setMethods(['isSubmited'])
////                ->getMock();
////        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
////
////       // $form->setMethod('post');
////
////        $element1 = $form->text('foo[var1]');
////        $element2 = $form->text('foo[var2]');
////        $element3 = $form->text('foo[var3]');
////        $element4 = $form->text('foo[]');
////        $element5 = $form->text('bar');
////        $element6 = $form->text('xyz');
////
////        $this->assertSame('barx', $element1->getAttribute('value'));
////        $this->assertSame('bzzz', $element2->getAttribute('value'));
////        $this->assertSame('12345', $element3->getAttribute('value'));
////        $this->assertSame('qwerty', $element4->getAttribute('value'));
////        $this->assertSame('rrrrr', $element5->getAttribute('value'));
////        $this->assertFalse($element6->getAttribute('value'));
////    }
////
////    public function test_validate_false() {
////        $form = $this->getMockBuilder(Forms::class)
////                ->setMethods(['isSubmited'])
////                ->getMock();
////        $form->expects($this->once())->method('isSubmited')->will($this->returnValue(false));
////        $this->assertFalse($form->validate());
////    }
//
//    public function test_validate_false_after_submit() {
//
//        $form = $this->getMockBuilder(Forms::class)
//                ->setMethods(['isSubmited'])
//                ->getMock();
//
//        $form->expects($this->once())->method('isSubmited')->will($this->returnValue(true));
//
//        $form->text('foo')->addRule('required');
//        $this->assertFalse($form->validate());
//    }
//
//    public function test_validate_true() {
//        $form = $this->getMockBuilder(Forms::class)
//                ->setMethods(['isSubmited'])
//                ->getMock();
//        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
//        $this->assertTrue($form->validate());
//    }
//
//    public function test_checkSubmittedFrom_true() {
//        $_GET[Forms::_TOKEN_SUBMIT_] = '0f62a94b667c962f7108e0c2dbc0c34a';
//        $form = new Forms('get', '/test.php');
//        $this->assertTrue($form->isSubmited());
//    }
//
//    public function test_checkSubmittedFrom_false() {
//        $_GET[Forms::_TOKEN_SUBMIT_] = 'bf6813c2bc0becb369a8d8367b6b77db';
//        $form = new Forms('get', '/test.php#');
//        $this->assertFalse($form->isSubmited());
//    }
//
//    public function test_addElements() {
//        $form = new Forms();
//        $form->addElements([
//            new \Enjoys\Forms\Elements\Text('foo'),
//            new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha(),
//        ]);
//        //more submit_token
//        $this->assertCount(3, $form->getElements());
//    }
//
//    public function test_formCount_1_0() {
//        $this->form = null;
//        $form1 = new Forms();
//        $this->assertSame(1, $form1->getFormCount());
//    }
//
//    public function test_formCount_1_1() {
//        $this->form = null;
//        $form1 = new Forms();
//        $this->assertNotSame(2, $form1->getFormCount());
//    }
//
//    public function test_formCount_2_0() {
//        $this->form = null;
//        $form1 = new Forms();
//        $form2 = new Forms();
//        $this->assertSame(1, $form1->getFormCount());
//        $this->assertSame(2, $form2->getFormCount());
//    }
//
//    public function test_formCount_2_1() {
//        $this->form = null;
//        $form1 = new Forms();
//        $form2 = new Forms();
//        $this->assertNotSame(2, $form1->getFormCount());
//        $this->assertNotSame(1, $form2->getFormCount());
//    }
}
