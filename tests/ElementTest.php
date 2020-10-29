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

declare(strict_types=1);

namespace Tests\Enjoys\Forms;


/**
 * Description of ElementTest
 *
 * @author deadl
 */
class ElementTest 
{
    use Reflection;

    /**
     *
     * @var  Enjoys\Forms\Forms $form 
     */
    protected $obj;

    protected function setUp(): void
    {
        $this->obj = new Form();
        if (isset($this->obj->getElements()[Form::_TOKEN_SUBMIT_])) {
            $this->obj->removeElement($this->obj->getElements()[Form::_TOKEN_SUBMIT_]);
        }

        if (isset($this->obj->getElements()[Form::_TOKEN_CSRF_])) {
            $this->obj->removeElement($this->obj->getElements()[Form::_TOKEN_CSRF_]);
        }
    }

    protected function tearDown(): void
    {
        $this->obj = null;
    }

    public function test_setName_1_0()
    {
        $element = new \Enjoys\Forms\Elements\Text('Foo');
        $this->assertEquals('Foo', $element->getName());
        $element->setName('Baz');
        $this->assertEquals('Baz', $element->getName());
    }

    public function test_setId_1_0()
    {
        $element = new \Enjoys\Forms\Elements\Text('Foo');
        $this->assertEquals('Foo', $element->getAttribute('id'));
        $element->setAttribute('id', 'Baz');
        $this->assertEquals('Baz', $element->getAttribute('id'));
    }

    public function test_getType_1_0()
    {
        $text = $this->obj->text('Foo');
        $this->assertEquals('text', $text->getType());
    }

   

    public function test_setTitle_1_0()
    {
        $element = new \Enjoys\Forms\Elements\Text('Foo', 'Bar');
        $this->assertEquals('Bar', $element->getLabel());
        $element->setLabel('Baz');
        $this->assertEquals('Baz', $element->getLabel());
    }

    public function test_setDescription_1_0()
    {
        $element = new \Enjoys\Forms\Elements\Text('Foo', 'Bar');
        $element->setDescription('Zed');
        $this->assertEquals('Zed', $element->getDescription());
    }

 
    public function test_setFormDefaults_1_1()
    {
        $this->obj->setDefaults([
            'Foo' => [
                'first_string', 'second_string'
            ]
        ]);
//        $this->obj->setOptions([
//            'defaults' => [
//                'Foo' => [
//                    'first_string', 'second_string'
//                ]
//            ]
//        ]);
        $element = $this->obj->text('Foo[]', 'Bar');
        $this->assertEquals('first_string', $element->getAttribute('value'));
    }

    public function test_addRule_1_0()
    {
        $element = $this->obj->text('Foo', 'Bar')->addRule(\Enjoys\Forms\Rules::REQUIRED, 'will be required');
        $this->assertEquals('will be required', $element->getRules()[0]->getMessage());
    }

    public function test_setRuleMessage_1_0()
    {
        $element = new \Enjoys\Forms\Elements\Text('Foo', 'Bar');
        $element->setRuleError('rule message error');
        $this->assertEquals('rule message error', $element->getRuleErrorMessage());
        $this->assertEquals(true, $element->isRuleError());
    }

    public function test_isrequired()
    {
        $element = new \Enjoys\Forms\Elements\Text('Foo', 'Bar');
        $this->assertEquals(false, $element->isRequired());
        $element->addRule(\Enjoys\Forms\Rules::REQUIRED);
        $this->assertEquals(true, $element->isRequired());
    }
}
