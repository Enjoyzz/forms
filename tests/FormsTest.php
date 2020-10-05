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

use Enjoys\Forms\Forms

;

/**
 * Description of FormsTest
 *
 * @author deadl
 */
class FormsTest extends \PHPUnit\Framework\TestCase
{

    use Reflection;

    /**
     *
     * @var  Enjoys\Forms\Forms $form 
     */
    protected $form;

    protected function setUp(): void {
        $this->form = new Forms();
        $this->form->removeElement('_token_submit');
    }

    protected function tearDown(): void {
        $this->form = null;

        unset($_POST);
        unset($_GET);
    }

    public function testSetRenderer() {
        $obj = $this->form->setRenderer('notfound');
        $this->assertObjectHasAttribute('renderer', $obj);
    }

    public function testGetElements() {

        $this->assertIsArray($this->form->getElements());
    }

    public function testSetAction() {
        $this->assertSame('/action.php', $this->form->setAction('/action.php')->getAttribute('action'));
    }

    /**
     * 
     * @dataProvider dataMethodProvider
     */
    public function testSetMethod($method, $expected) {
        $result = $this->form->setMethod($method);
        $this->assertSame($expected, $this->form->getMethod());
        $this->assertIsObject($result);
    }

    public function dataMethodProvider() {
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
     * @dataProvider dataForConstruct
     */
    public function testConstruct($method, $action, $expectedMethod, $expectedAction) {
        $this->form = new Forms($method, $action);
        $this->assertSame($expectedMethod, $this->form->getAttribute('method'));
        $this->assertSame($expectedAction, $this->form->getAttribute('action'));
    }

    public function dataForConstruct() {
        return [
            ['get', '/action.php', 'GET', '/action.php'],
            ['get', null, 'GET', false],
            [null, null, false, false],
            ['Post', null, 'POST', false],
        ];
    }

    /**
     * 
     * @dataProvider dataProviderSetName
     */
    public function testSetName($name, $expected) {

        $result = $this->form->setName($name);
        $this->assertSame($expected, $this->form->getAttribute('name'));
        $this->assertIsObject($result);
        $this->assertTrue($result->getName() === null || is_string($result->getName()));
    }

    public function dataProviderSetName() {
        return [
            ['formname', 'formname'],
            [null, false]
        ];
    }

    /**
     * 
     * @dataProvider dataCallValid
     */
    public function test_text_call($call) {
        //$this->expectException(\Enjoys\Forms\Exception::class);
        $result = $this->form->$call('test', 'testname');
        $this->assertInstanceOf('\Enjoys\Forms\Element', $result);
    }

    public function dataCallValid() {
        return [
            ['text'], ['hidden'], ['password'], ['submit'], ['checkbox'],
//            ['button'], ['file'], ['image'], ['radio'], ['reset'], ['color'],
//            ['date'], ['datetime'], ['datetime-local'], ['email'], ['number'],
//            ['range'], ['search'], ['tel'], ['time'], ['url'], ['month'], ['week']
        ];
    }

    public function test_invalid_call() {
        $this->expectException(\Enjoys\Forms\Exception::class);
        $this->form->invalid();
    }

    public function test_double_set_elements() {
        $this->expectException(\Enjoys\Forms\Exception::class);
        $this->form->text('foo');
        $this->form->text('foo');
    }

    public function test_invalid_display() {
        $this->expectException(\Enjoys\Forms\Exception::class);
        $this->form->setRenderer('invalid')->display();
    }

    public function test_valid_display() {

        $result = $this->form->setRenderer('defaults')->display();
        $this->assertTrue($result instanceof \Enjoys\Forms\Renderer\Defaults);
    }

    public function test_count_elements() {
        $this->form->text('foo');
        $this->form->hidden('bar');
        $this->form->password('baz');
        $this->assertCount(3, $this->form->getElements());
    }

    public function test_remove_elements() {
        $this->form->text('foo');
        $this->form->hidden('bar');
        $this->form->removeElement('foo');
        $this->assertCount(1, $this->form->getElements());
    }

    public function test_set_default() {
        $this->form->setDefaults([
            'foo' => 'bar'
        ]);
        $element = $this->form->text('foo');
        $this->assertSame('bar', $element->getAttribute('value'));
    }

    public function test_set_default2() {
        $this->form->setDefaults([
            'foo' => 'bar'
        ]);
        $element = $this->form->text('foo')->setName('baz');
        $this->assertSame('bar', $element->getAttribute('value'));
    }

    public function test_set_isSubmit() {

        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();

        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
        unset($_GET);
        $_GET['foo'] = 'baz';
        $form->setDefaults([
            'foo' => 'bar'
        ]);

        $element = $form->text('foo');

        $this->assertSame('baz', $element->getAttribute('value'));
    }

    public function test_set_default3_0() {
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();



        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));

        $_GET['zed'] = 'anystring';

        $form->setDefaults([
            'foo' => 'bar'
        ]);

        $element = $form->text('foo')->setName('zed');

        $this->assertSame('anystring', $element->getAttribute('value'));
    }

    public function test_set_default3_1() {
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();

        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));


        $_GET['zed'] = 'anystring';

        $form->setDefaults([
            'foo' => 'bar'
        ]);

        $element = $form->text('foo')->addAttributes([
            'name' => 'zed',
        ]);

        $this->assertSame('anystring', $element->getAttribute('value'));
    }

    public function test_set_default5() {
        $_GET['foo'] = 'bar';
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();

        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));

        $form->setDefaults([]);
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttribute('value'));
    }

    public function test_set_default6() {
        $_POST['foo'] = 'barx';
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();
        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));

        $form->setMethod('post');



        $element = $form->text('foo');
        $this->assertSame('barx', $element->getAttribute('value'));
    }

    public function test_set_default7_array() {
        //$this->markTestSkipped('не доработан фунционал');
        $_POST['foo']['var1'] = 'barx';
        $_POST['foo']['var2'] = 'bzzz';
        $_POST['foo'][0] = 'qwerty';
        $_POST['foo']['var3'] = '12345';
        $_POST['bar'] = 'rrrrr';
        $_POST['xyz'][0] = 'valid';
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();
        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));

        $form->setMethod('post');

        $element1 = $form->text('foo[var1]');
        $element2 = $form->text('foo[var2]');
        $element3 = $form->text('foo[var3]');
        $element4 = $form->text('foo[]');
        $element5 = $form->text('bar');
        $element6 = $form->text('xyz');

        $this->assertSame('barx', $element1->getAttribute('value'));
        $this->assertSame('bzzz', $element2->getAttribute('value'));
        $this->assertSame('12345', $element3->getAttribute('value'));
        $this->assertSame('qwerty', $element4->getAttribute('value'));
        $this->assertSame('rrrrr', $element5->getAttribute('value'));
        $this->assertFalse($element6->getAttribute('value'));
    }

    public function test_validate_false() {
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();
        $form->expects($this->once())->method('isSubmited')->will($this->returnValue(false));
        $this->assertFalse($form->validate());
    }

    public function test_validate_false_after_submit() {
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();
        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
        $form->text('foo')->addRule('required');
        $this->assertFalse($form->validate());
    }

    public function test_validate_true() {
        $form = $this->getMockBuilder(Forms::class)
                ->setMethods(['isSubmited'])
                ->getMock();
        $form->expects($this->any())->method('isSubmited')->will($this->returnValue(true));
        $this->assertTrue($form->validate());
    }

    public function test_checkSubmittedFrom_true() {
        $_GET[Forms::_TOKEN_SUBMIT_] = 'bf6813c2bc0becb369a8d8367b6b77db';
        $form = new Forms('get', '/test.php');
        $this->assertTrue($form->isSubmited());
    }

    public function test_checkSubmittedFrom_false() {
        $_GET[Forms::_TOKEN_SUBMIT_] = 'bf6813c2bc0becb369a8d8367b6b77db';
        $form = new Forms('get', '/test.php#');
        $this->assertFalse($form->isSubmited());
    }

    public function test_addElements() {
        $form = new Forms();
        $form->addElements([
            new \Enjoys\Forms\Elements\Text('foo'),
            new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha(),
        ]);
        //more submit_token
        $this->assertCount(3, $form->getElements());
    }
    
    /**
     * @dataProvider dataStringValueForSetDefault
     */
    public function test_getStringValueForSetDefault($path, $expect) {
        $arrays = [
            'foo' => [
                'bar' => 'bar1',
                'name' => 'myname',
                4,
                'test' => [
                    '3' => 55
                ]
            ],
            'notarray' => 'yahoo',
            'array' => [
                
            ]
        ];
        $element = new \Enjoys\Forms\Element('test', 'test');
        $method = $this->getPrivateMethod(\Enjoys\Forms\Element::class, 'getStringValueForSetDefault');
        $result = $method->invokeArgs($element, [
            $path,
            $arrays
        ]);
        $this->assertEquals($expect, $result);
    }
    
    public function dataStringValueForSetDefault() {
        return [
            ['foo[bar]', 'bar1'],
            ['foo[name]', 'myname'],
            ['foo[]', 4],
            ['foo[0]', 4],
            ['foo[test][3]', 55],
            ['foo[test][3]', '55'],
            ['notarray', 'yahoo'],
            ['array', false],
            ['/invalide_string/', false],
        ];
    }    

}
