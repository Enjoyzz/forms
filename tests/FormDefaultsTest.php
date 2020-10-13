<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
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

/**
 * Class FormDefaultsTest
 *
 * @author Enjoys
 */
class FormDefaultsTest extends \PHPUnit\Framework\TestCase
{

    use Reflection;

    public function tearDown(): void
    {
        $_GET = [];
    }

    public function test_construct_with_submitted()
    {

        $form = new \Enjoys\Forms\Form();
        

        $submited_form = $this->getPrivateProperty(\Enjoys\Forms\Form::class, 'submited_form');
        $submited_form->setValue($form, true);

        $property = $this->getPrivateProperty(\Enjoys\Forms\Form::class, 'request');
        $property->setValue($form, new \Enjoys\Forms\Http\Request(['data' => 'changeddata'], []));

        $form->setDefaults([
            'data' => 'data'
        ]);

        //$property->getValue($form)->get()
        $formDefaults = $this->getPrivateProperty(\Enjoys\Forms\Form::class, 'formDefaults');

        $this->assertEquals('changeddata', $formDefaults->getValue($form)->getValue('data'));
    }

    public function test_construct_with_notsubmitted()
    {
        $form = $this->getMockBuilder(\Enjoys\Forms\Form::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['isSubmited', 'getMethod'])
                ->getMock();
        $form->method('isSubmited')->will($this->returnValue(false));
        $form->method('getMethod')->will($this->returnValue('GET'));

        $defaults = new \Enjoys\Forms\FormDefaults([
            'data' => 'data'
                ], $form);


        $this->assertEquals('data', $defaults->getValue('data'));
    }

    //put your code here
}
