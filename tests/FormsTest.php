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
class FormsTest extends \PHPUnit\Framework\TestCase {

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

}
