<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Elements\Option;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Form;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;
use Tests\Enjoys\Forms\Reflection;

class SelectTest extends _TestCase
{
    use Reflection;

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
        $this->assertSame('v1', $v1->getAttribute('value')->getValueString());
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
        $this->assertSame('i2', $v2->getAttribute('id')->getValueString());
    }

    public function test_fill7()
    {
        $this->markAsRisky('Проверить тест');
        $elements = $this->filldata();
        /** @var Option $v2 */
        $v2 = $elements[1];
        $this->assertSame('', $v2->getAttribute('disabled')->getValueString());
        $this->assertNotNull($v2->getAttribute('id'));
    }

    public function test_count_option_element()
    {
        $obj = new Select('name', 'title');
        $obj->fill([
            1,
            2,
            3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_option_element2()
    {
        $obj = new Select('name', 'title');
        $obj->fill([
            1,
            1,
            3
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
        $obj->setAttribute(AttributeFactory::create('multiple'));

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_name_add_array_2_1()
    {
        $obj = new Select('name', 'title');
        $obj->setMultiple();

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_name_add_array_1_2()
    {
        $obj = new Select('name[]', 'title');
        $obj->setAttribute(AttributeFactory::create('multiple'));

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name[]', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_name_add_array2()
    {
        $obj = new Select('name', 'title');
        $obj->setAttribute(AttributeFactory::create('multiple'));
        $obj->setAttribute(AttributeFactory::create('disabled'));

        $this->assertSame('name[]', $obj->getName());
        $this->assertSame('name', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_id_begin_id()
    {
        $obj = new Select('name[]', 'title');
        $obj->setAttribute(AttributeFactory::create('id', 'test'));
        $obj->setAttribute(AttributeFactory::create('multiple'));
        $this->assertSame('test', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_id_begin_id2()
    {
        $obj = new Select('name', 'title');
        $obj->setAttribute(AttributeFactory::create('id', 'test'));
        $obj->setAttribute(AttributeFactory::create('multiple'));
        $this->assertSame('test', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_begin_multiple()
    {
        $obj = new Select('name[]', 'title');
        $obj->setAttribute(AttributeFactory::create('multiple'))
            ->setAttribute(AttributeFactory::create('id', 'test'));
        $this->assertSame('test', $obj->getAttribute('id')->getValueString());
    }

    public function test_multiple_begin_multiple2()
    {
        $obj = new Select('name', 'title');
        $obj->setAttributes(AttributeFactory::createFromArray([
            'multiple',
            'id' => 'test'
        ]));
        $this->assertSame('test', $obj->getAttribute('id')->getValueString());
    }

    public function test_id()
    {
        $obj = new Select('name', 'title');
        $obj->setAttribute(AttributeFactory::create('id', 'test'));
        $this->assertSame('test', $obj->getAttribute('id')->getValueString());
    }

    public function test_defaults1()
    {
        $form = new Form();
        $form->setDefaults([
            'name' => 2
        ]);
        $form->select('name', 'title')->fill([
            1,
            2,
            3
        ], true);

        /** @var Select $select */
        $select = $form->getElement('name');

        $this->assertNotNull($select->getElements()[1]->getAttribute('selected'));
        $this->assertNull($select->getElements()[0]->getAttribute('selected'));
    }

    public function test_defaults2()
    {
        $form = new Form();
        $form->setDefaults([
            'name' => [1, 2]
        ]);
        $select = $form->select('name[]', 'title')->fill([
            1,
            2,
            3
        ], true);

        /** @var Select $select */
        $this->assertNotNull($select->getElements()[0]->getAttribute('selected'));
        $this->assertNotNull($select->getElements()[1]->getAttribute('selected'));
        $this->assertNull($select->getElements()[2]->getAttribute('selected'));
    }

    public function testDefaultsAttrMultipleBeforeFill()
    {
        $form = new Form();
        $form->setDefaults([
            'name2' => [1, 3]
        ]);
        $form->select('name2', 'title')
            ->setAttribute(AttributeFactory::create('multiple'))
            ->fill([1, 2, 3], true)
        ;

        /** @var Select $select */
        $select = $form->getElement('name2[]');
        $this->assertNotNull($select->getElements()[0]->getAttribute('selected'));
        $this->assertNull($select->getElements()[1]->getAttribute('selected'));
        $this->assertNotNull($select->getElements()[2]->getAttribute('selected'));
    }

    public function testDefaultsAttrMultipleAfterFill()
    {
        $form = new Form();
        $form->setDefaults([
            'name2' => [1, 3]
        ]);
        $form->select('name2', 'title')
            ->fill([1, 2, 3], true)
            ->setAttribute(AttributeFactory::create('multiple'))
        ;

        /** @var Select $select */
        $select = $form->getElement('name2[]');
        $this->assertNotNull($select->getElements()[0]->getAttribute('selected'));
        $this->assertNull($select->getElements()[1]->getAttribute('selected'));
        $this->assertNotNull($select->getElements()[2]->getAttribute('selected'));
    }

    public function testOptgroup()
    {
        $select = new Select('name');
        $select->setOptgroup('foo', [
            1,
            2,
            3
        ], [], true);

        $this->assertInstanceOf('\Enjoys\Forms\Elements\Optgroup', $select->getElements()[0]);
        $options = $select->getElements()[0]->getElements();
        $this->assertInstanceOf('\Enjoys\Forms\Elements\Option', $options[0]);
    }

    public function testSetDefaultIfSubmitted()
    {
        $request = new ServerRequest(parsedBody: [
            'name' => 3,
        ]);

        $form = new Form(request: $request);
        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setAccessible(true);
        $submitted->setValue($form, true);

        $form->setDefaults([
            'name' => [1, 2]
        ]);
        $radio = $form->select('name', 'title')->fill([1, 2, 3], true);
        $elements = $radio->getElements();
        $this->assertNull($elements[0]->getAttribute('selected'));
        $this->assertNull($elements[1]->getAttribute('selected'));
        $this->assertNotNull($elements[2]->getAttribute('selected'));
    }


    /**
     * Работает, если все элементы с одинаковыми иманами будут multiple
     * @return void
     */
    public function testElementsWithSameNames()
    {
        $form = new Form();
        $form->removeElement($form->getElement(Form::_TOKEN_SUBMIT_));
        $form->removeElement($form->getElement(Form::_TOKEN_CSRF_));

        $form->setDefaults([
            'foo' => [
                2,4
            ]
        ]);

        $element = $form->select('foo', 'foo1')->setMultiple()->fill([1,2,3], true);
        $element2 = $form->select('foo', 'foo2')->setMultiple()->fill([4,5,6], true);

        $this->assertCount(2, $form->getElements());

        $this->assertNull($element->getElements()[0]->getAttribute('selected'));
        $this->assertNotNull($element->getElements()[1]->getAttribute('selected'));
        $this->assertNull($element->getElements()[2]->getAttribute('selected'));
        $this->assertNotNull($element2->getElements()[0]->getAttribute('selected'));
        $this->assertNull($element2->getElements()[1]->getAttribute('selected'));
        $this->assertNull($element2->getElements()[2]->getAttribute('selected'));
    }
}
