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

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Form;
use Enjoys\Http\ServerRequest;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;

/**
 * Description of GroupTest
 *
 * @author Enjoys
 */
class GroupTest extends \PHPUnit\Framework\TestCase
{

    public function test_init_group()
    {
        $g = new \Enjoys\Forms\Elements\Group('group1');
        $g->setForm(new \Enjoys\Forms\Form());
        $g->add(
            [
                new \Enjoys\Forms\Elements\Text('foo', 'bar')
            ]
        );
        $this->assertInstanceOf('\Enjoys\Forms\Element', $g->getElements()['foo']);
    }

    public function test_invalid_element()
    {
        $this->expectException('\Enjoys\Forms\Exception\ExceptionElement');
        $g = new \Enjoys\Forms\Elements\Group('group1');
        $g->invalid();
    }

    public function test_setDefaultsArraysForGroupSelect()
    {
        $this->markTestSkipped('Проверить тест');
        $request = new ServerRequestWrapper(
            ServerRequestCreator::createFromGlobals(
                ['REQUEST_METHOD' => 'POST'],
                null,
                null,
                null,
                [
                    'foo1' => 'a',
                    'foo2' => [
                        1 => [
                            'test' => [
                                2 => 'b'
                            ]
                        ]
                    ],
                    'foo3' => [
                        1 => 'c'
                    ],
                ]
            )
        );

        $tockenSubmitMock = $this->getMockBuilder(TockenSubmit::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tockenSubmitMock->expects($this->once())->method('getSubmitted')->will($this->returnValue(true));

        $form = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->addMethods(['tockenSubmit'])
            ->getMock();

        $form->expects($this->once())->method('tockenSubmit')->will(
            $this->returnCallback(
                function () use ($tockenSubmitMock) {
                    return $tockenSubmitMock;
                }
            )
        );

        $form->__construct(
            [
                'method' => 'post'
            ],
            $request
        );


        $fill = [
            'false',
            'a',
            'b',
            'c',
            'd'
        ];

        $element1 = (new Select('foo1'))->fill($fill, true);
        $element2 = (new Select('foo2[1][test][2]'))->fill($fill, true);
        $element3 = (new Text('foo3[1]'));

        $form->group('group')->add(
            [
                $element1,
                $element2,
                $element3,
            ]
        );

        $this->assertEquals('POST', $form->getRequestWrapper()->getRequest()->getMethod());

        $this->assertNull($element1->getElements()[1]->getAttr('selected'));
        $this->assertNull($element2->getElements()[2]->getAttr('selected'));
        $this->assertSame('c', $element3->getAttr('value')->getValueString());
    }
}
