<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Option;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Form;


class SelectTest
{

    public function test_title()
    {
        $obj = new Select('name', 'title');
        $this->assertSame('title', $obj->getLabel());
    }

    public function test_title2()
    {
        $obj = new Select('name', 'title');
        $obj->setLabel('title2');
        $this->assertSame('title2', $obj->getLabel());
    }

    public function test_name()
    {
        $obj = new Select('name', 'title');
        $this->assertSame('name', $obj->getName());
    }

    private function filldata()
    {
        $obj = new Select('name', 'title');
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

    public function test_fill()
    {

        $elements = $this->filldata();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('t1', $v1->getLabel());
    }

    public function test_fill3()
    {

        $elements = $this->filldata();
        /** @var Option $v1 */
        $v1 = $elements[0];
        $this->assertSame('v1', $v1->getAttr('id')->getValueString());
    }

    public function test_fill4()
    {

        $elements = $this->filldata();
        /** @var Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('t2', $v2->getLabel());
    }

    public function test_fill5()
    {

        $elements = $this->filldata();
        /** @var Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('v2', $v2->getName());
    }

    public function test_fill6()
    {

        $elements = $this->filldata();
        /** @var Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('i2', $v2->getAttr('id')->getValueString());
    }

    public function test_fill7()
    {
        $this->markAsRisky('Проверить тест');
        $elements = $this->filldata();
        /** @var Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('', $v2->getAttr('disabled')->getValueString());
        $this->assertNotNull($v2->getAttr('id'));
    }

    public function test_count_option_element()
    {

        $obj = new Select('name', 'title');
        $obj->fill([
            1, 2, 3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_option_element2()
    {

        $obj = new Select('name', 'title');
        $obj->fill([
            1, 1, 3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_option_element3()
    {

        $obj = new Select('name', 'title');
        $obj->fill([1], true)->fill([1], true);

        $this->assertCount(2, $obj->getElements());
    }

    public function test_multiple_name_add_array()
    {

        $obj = new Select('name', 'title');
        $obj->setAttr(new Attribute('multiple'));

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_name_add_array_2_1()
    {

        $obj = new Select('name', 'title');
        $obj->setMultiple();

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_name_add_array_1_2()
    {

        $obj = new Select('name[]', 'title');
        $obj->setAttr(new Attribute('multiple'));

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name[]', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_name_add_array2()
    {

        $obj = new Select('name', 'title');
        $obj->setAttr(new Attribute('multiple'));
        $obj->setAttr(new Attribute('disabled'));

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_id_begin_id()
    {

        $obj = new Select('name[]', 'title');
        $obj->setAttr(new Attribute('id', 'test'));
        $obj->setAttr(new Attribute('multiple'));
        $this->assertSame('test', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_id_begin_id2()
    {

        $obj = new Select('name', 'title');
        $obj->setAttr(new Attribute('id', 'test'));
        $obj->setAttr(new Attribute('multiple'));
        $this->assertSame('test', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_begin_multiple()
    {

        $obj = new Select('name[]', 'title');
        $obj->setAttr(new Attribute('multiple'));
        $obj->setAttr(new Attribute('id', 'test'));
        $this->assertSame('test', $obj->getAttr('id')->getValueString());
    }

    public function test_multiple_begin_multiple2()
    {

        $obj = new Select('name', 'title');
        $obj->setAttr(new Attribute('multiple'));
        $obj->setAttr(new Attribute('id', 'test'));
        $this->assertSame('test', $obj->getAttr('id')->getValueString());
    }

    public function test_id()
    {

        $obj = new Select('name', 'title');
        $obj->setAttr(new Attribute('id', 'test'));
        $this->assertSame('test', $obj->getAttr('id')->getValueString());
    }

    public function test_defaults1()
    {
        $this->markTestSkipped('Проверить тест');
        $form = new Form();
        $form->setOption('Defaults', [
            'name' => 2
        ]);
        $form->select('name', 'title')->fill([
            1, 2, 3
        ], true);

        /** @var Select $select */
        $select = $form->getElements()['name'];
        /** @var Option $option */
        $option = $select->getElements()[1];
        $this->assertNull($option->getAttr('selected')?->getValueString());
    }

    public function test_defaults2()
    {
        $this->markTestSkipped('Проверить тест');
        $form = new Form();
        $form->setOption('Defaults', [
            'name' => [1, 2]
        ]);
        $select = $form->select('name[]', 'title')->fill([
            1, 2, 3
        ], true);

        /** @var Select $select */
        $this->assertNull($select->getElements()[0]->getAttr('selected'));
        $this->assertNull($select->getElements()[1]->getAttr('selected'));
    }

    public function test_defaults3()
    {
        $this->markTestSkipped('Проверить тест');
        $form = new Form();
        $form->setOption('Defaults', [
            'name' => [1, 2]
        ]);
        $form->select('name', 'title')->fill([
            1, 2, 3
        ], true);

        /** @var Select $select */
        $select = $form->getElements()['name'];
        $this->assertNull($select->getElements()[0]->getAttr('selected'));
        $this->assertNull($select->getElements()[1]->getAttr('selected'));
    }

    public function test_defaults4_attr_before_fill()
    {
        $this->markTestSkipped('Проверить тест');
        $form = new Form();
        $form->setDefaults([
            'name2' => [1, 3]
        ]);
        $form->select('name2', 'title')
                ->setAttributes(['multiple'])
                ->fill([1, 2, 3], true)
        ;

        /** @var Select $select */
        $select = $form->getElements()['name2'];
        $this->assertNull($select->getElements()[0]->getAttr('selected'));
        $this->assertFalse($select->getElements()[1]->getAttr('selected'));
        $this->assertNull($select->getElements()[2]->getAttr('selected'));
    }

//    public function test_defaults4_attr_after_fill()
//    {
//        $this->markTestSkipped('Не корректно работает, тест выше, почти такое же корректно работает');
//        $form = new \Enjoys\Forms\Form();
//        $form->setDefaults([
//            'name2' => [0, 2]
//        ]);
//        $form->select('name2', 'title')
//                ->fill([1, 2, 3], true)
//                ->setAttributes(['multiple'])
//        ;
//
//        /** @var \Enjoys\Forms\Elements\Select $select */
//        $select = $form->getElements()['name2'];
//        $this->assertNull($select->getElements()[0]->getAttr('selected'));
//        $this->assertFalse($select->getElements()[1]->getAttr('selected'));
//        $this->assertNull($select->getElements()[2]->getAttr('selected'));
//    }

    public function test_optgroup()
    {
        $select = new Select('name');
        $select->setOptgroup('foo', [
            1, 2, 3
        ], [],true);

        $this->assertInstanceOf('\Enjoys\Forms\Elements\Optgroup', $select->getElements()[0]);
        $options = $select->getElements()[0]->getElements();
        $this->assertInstanceOf('\Enjoys\Forms\Elements\Option', $options[0]);
    }
}
