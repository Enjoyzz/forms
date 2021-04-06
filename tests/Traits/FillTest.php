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

namespace Tests\Enjoys\Forms\Traits;

/**
 * Class FillTest
 *
 * @author Enjoys
 */
class FillTest extends \PHPUnit\Framework\TestCase
{
    use \Tests\Enjoys\Forms\Reflection;

    public function test_setIndexKeyFill()
    {
        $select = new \Enjoys\Forms\Elements\Select('select');
        $select->fill(
            [
                1,
                2,
                3
            ],
            true
        );
        $this->assertEquals('1', $select->getElements()[0]->getName());
        $this->assertEquals('2', $select->getElements()[1]->getName());
        $this->assertEquals('3', $select->getElements()[2]->getName());

        $select->fill(
            [
                1,
                2,
                3
            ],
            true
        );
        $this->assertEquals('1', $select->getElements()[3]->getName());
        $this->assertEquals('2', $select->getElements()[4]->getName());
        $this->assertEquals('3', $select->getElements()[5]->getName());

        $this->assertEquals('select', $select->getElements()[5]->getParentName());
    }

    public function test_setIndexKeyFillIntAsValue()
    {
        $select = new \Enjoys\Forms\Elements\Select('select');
        $select->fill(
            [
                46 => 1,
                2,
                3
            ]
        );
        $this->assertEquals('46', $select->getElements()[0]->getAttribute('value'));
        $this->assertEquals('47', $select->getElements()[1]->getAttribute('value'));
        $this->assertEquals('48', $select->getElements()[2]->getAttribute('value'));

    }

//    /**
//     * @dataProvider elements
//     */
//    public function test_fill_with_attributes($el, $name)
//    {
//        $this->markTestSkipped('Не нужен тест, он больше фунциональный');
//        $class = '\Enjoys\Forms\Elements\\' . $el;
//        $element = new $class('foo');
//        $element->fill([
//            'test' => [
//                'title1', 
//                [
//                    'disabled'
//                ]
//            ], 
//            'foz' => [
//                2, 
//                [
//                    'id' => 'newfoz'
//                ]
//            ], 
//            'baz' => 3
//        ]);
//        $this->assertEquals(null, $element->getElements()[0]->getAttribute('disabled'));
//        $this->assertEquals(false, $element->getElements()[1]->getAttribute('disabled'));
//        $this->assertEquals('newfoz', $element->getElements()[1]->getAttribute('id'));
//        $this->assertEquals($name, $element->getElements()[2]->getParentName());
//    }
//
//    public function elements()
//    {
//        return [
//            ['select', 'foo'],
//            ['radio', 'foo'],
//            ['checkbox', 'foo[]'],
//        ];
//    }
}
