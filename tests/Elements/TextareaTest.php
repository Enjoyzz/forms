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

namespace Tests\Enjoys\Forms\Elements;

/**
 * Class TextareaTest
 *
 * @author Enjoys
 */
class TextareaTest extends \PHPUnit\Framework\TestCase
{

    public function test_init_textarea()
    {
        $el = new \Enjoys\Forms\Elements\Textarea('foo', 'title1');
        $this->assertTrue($el instanceof \Enjoys\Forms\Elements\Textarea);
        $this->assertEquals('foo', $el->getName());
//        $this->assertEquals('foo', $el->getValidateName());
        $this->assertEquals('title1', $el->getLabel());
        $el->setValue('text');
        $this->assertEquals('text', $el->getValue());
        $el->setValue('text2');
        $this->assertEquals('text2', $el->getValue());
        $el->setAttributes(['class' => 'textarea_class']);
        $this->assertEquals('textarea_class', $el->getAttribute('class'));
        $this->assertEquals(['textarea_class'], $el->getClassesList());
        $el->addClass('textarea_class2');
        $this->assertEquals('textarea_class textarea_class2', $el->getAttribute('class'));
    }

    public function test_setCols()
    {
        $el = new \Enjoys\Forms\Elements\Textarea('foo');
        $el->setCols(5);
        $this->assertEquals('5', $el->getAttribute('cols'));
        $el->setCols('25');
        $this->assertEquals('25', $el->getAttribute('cols'));
    }

    public function test_setRows()
    {
        $el = new \Enjoys\Forms\Elements\Textarea('foo');
        $el->setRows(50);
        $this->assertEquals('50', $el->getAttribute('rows'));
        $el->setRows('250');
        $this->assertEquals('250', $el->getAttribute('rows'));
    }

    public function test_basehtml()
    {
        $el = (new \Enjoys\Forms\Elements\Textarea('foo', 'bar'))->setValue('text2');
        $this->assertEquals('<textarea id="foo" name="foo">text2</textarea>', $el->baseHtml());
    }

    public function test_basehtml2()
    {
        $form = new \Enjoys\Forms\Form();
        $form->setDefaults([
            'foo' => 'baz'
        ]);
        $el = $form->textarea('foo');
        $this->assertEquals('<textarea id="foo" name="foo">baz</textarea>', $el->baseHtml());
    }
}
