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

namespace Tests\Enjoys\Forms\Elements;

/**
 * Description of SelectTest
 *
 * @author deadl
 */
class SelectTest extends \PHPUnit\Framework\TestCase {

    public function test_title() {
        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $this->assertSame('title', $obj->getTitle());
    }

    public function test_title2() {
        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->setTitle('title2');
        $this->assertSame('title2', $obj->getTitle());
    }

    public function test_name() {
        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $this->assertSame('name', $obj->getName());
    }

    public function test_name2() {
        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->setName('name[]');
        $this->assertSame('name[]', $obj->getName());
    }

    private function filldata() {
        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->fill([
            'v1' => 't1',
            'v2' => [
                't2',
                [
                    'id' => 'i2',
                    'disabled'
                ]
            ]
        ]);
        $elements = $obj->getElements();
        return $elements;
    }

    public function test_fill() {

        $elements = $this->filldata();
        /** @var \Enjoys\Forms\Elements\Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('t1', $v1->getTitle());
    }

    public function test_fill3() {

        $elements = $this->filldata();
        /** @var \Enjoys\Forms\Elements\Option $v1 */
        $v1 = $elements[0];
        $this->assertSame('v1', $v1->getId());
    }

    public function test_fill4() {

        $elements = $this->filldata();
        /** @var \Enjoys\Forms\Elements\Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('t2', $v2->getTitle());
    }

    public function test_fill5() {

        $elements = $this->filldata();
        /** @var \Enjoys\Forms\Elements\Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('v2', $v2->getName());
    }

    public function test_fill6() {

        $elements = $this->filldata();
        /** @var \Enjoys\Forms\Elements\Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('i2', $v2->getId());
    }

    public function test_fill7() {

        $elements = $this->filldata();
        /** @var \Enjoys\Forms\Elements\Option $v2 */
        $v2 = $elements[1];
        $this->assertNull($v2->getAttribute('disabled'));
        $this->assertNotNull($v2->getAttribute('id'));
    }

    public function test_count_option_element() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->fill([
            1, 2, 3
        ]);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_option_element2() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->fill([
            1, 1, 3
        ]);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_option_element3() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->fill([1])->fill([1]);

        $this->assertCount(2, $obj->getElements());
    }

    public function test_multiple_name_add_array() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->addAttribute('multiple');

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name[]', $obj->getId());
    }

    public function test_multiple_name_add_array2() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->addAttribute('multiple');
        $obj->addAttribute('disabled');

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name[]', $obj->getId());
    }

    public function test_multiple_id() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->setId('test');
        $obj->addAttribute('multiple');
        $this->assertSame('test', $obj->getId());
    }

    public function test_multiple_id2() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->addAttribute('multiple');
        $obj->setId('test');
        $this->assertSame('test', $obj->getId());
    }

    public function test_id() {

        $obj = new \Enjoys\Forms\Elements\Select('name', 'title');
        $obj->setId('test');
        $this->assertSame('test', $obj->getId());
    }

    public function test_defaults1() {
        $form = new \Enjoys\Forms\Forms();
        $form->setDefaults([
            'name' => 2
        ]);
        $form->select('name', 'title')->fill([
            1, 2, 3
        ]);

        /** @var \Enjoys\Forms\Elements\Select $select */
        $select = $form->getElements()['name'];
        /** @var \Enjoys\Forms\Elements\Option $option */
        $option = $select->getElements()[2];
        $this->assertNull($option->getAttribute('selected'));
    }

    public function test_defaults2() {
        $form = new \Enjoys\Forms\Forms();
        $form->setDefaults([
            'name' => [1, 2]
        ]);
        $form->select('name', 'title')->fill([
            1, 2, 3
        ]);

        /** @var \Enjoys\Forms\Elements\Select $select */
        $select = $form->getElements()['name'];
        $this->assertNull($select->getElements()[1]->getAttribute('selected'));
        $this->assertNull($select->getElements()[2]->getAttribute('selected'));
    }

}
