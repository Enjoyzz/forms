<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Traits\Reflection;


class ElementTest extends _TestCase
{
    use Reflection;


    public function testGetForm()
    {
        $element = $this->getMockForAbstractClass(Element::class, ['foo']);
        $form = new Form(action: '/action');
        $element->setForm($form);
        $this->assertEquals($form, $element->getForm());
    }

    public function testPrepare()
    {
        $element = $this->getMockForAbstractClass(Element::class, ['foo']);
        $form = new Form(action: '/action');
        $element->setForm($form);
        $element->prepare();
        $this->assertNotEquals($form, $element->getForm());
    }

    public function testUnsetForm()
    {
        $element = $this->getMockForAbstractClass(Element::class, ['foo']);
        $form = new Form(action: '/action');
        $element->setForm($form);
        $element->unsetForm();

        $privateProperty = $this->getPrivateProperty(Element::class, 'form');
        $this->assertNull($privateProperty->getValue($element));
    }

    public function testGetType()
    {
        $element = $this->getMockBuilder(Element::class)->setConstructorArgs([
            'name' => 'Foo'
        ])->onlyMethods(['getType'])->getMock();

        $element->expects($this->once())
            ->method('getType')
            ->willReturn('option');

        $this->assertSame('option', $element->getType());
    }

    public function testSetNameAndGetName()
    {
        $element = $this->getMockForAbstractClass(Element::class, [
            'name' => 'Foo'
        ]);
        $this->assertSame('Foo', $element->getName());
    }

    public function testSetLabelAndGetLabel()
    {
        $element = $this->getMockForAbstractClass(Element::class, [
            'name' => 'Foo',
            'label' => 'barzzzz'
        ]);
        $this->assertSame('barzzzz', $element->getLabel());
    }

    public function testBaseHtml()
    {
        $element = $this->getMockBuilder(Element::class)->setConstructorArgs([
            'name' => 'Foo'
        ])->onlyMethods(['getType'])->getMock();

        $element->expects($this->once())
            ->method('getType')
            ->willReturn('text');

        $element->getAttributeCollection()
            ->remove('id')
            ->remove('name');
        $this->assertEquals('<input type="text">', $element->baseHtml());
    }

    public function testSetName()
    {
        $element = $this->getMockForAbstractClass(Element::class, [
            'name' => 'Foo'
        ]);
        $this->assertEquals('Foo', $element->getName());
    }

    public function testSetId()
    {
        $element = $this->getMockForAbstractClass(Element::class, [
            'name' => 'Foo'
        ]);
        $this->assertSame('Foo', $element->getAttr('id')->getValueString());
        $element->setAttr(AttributeFactory::create('id', 'Baz'));
        $this->assertSame('Baz', $element->getAttr('id')->getValueString());
    }

    public function test_getType_1_0()
    {
        $element = $this->getMockBuilder(Element::class)->setConstructorArgs([
            'name' => 'Foo'
        ])->onlyMethods(['getType'])->getMock();

        $element->expects($this->once())
            ->method('getType')
            ->willReturn('option');

        $this->assertEquals('option', $element->getType());

    }

    public function testSetLabel()
    {
        $element = $this->getMockForAbstractClass(Element::class, [
            'name' => 'Foo',
            'label' => 'Bar'
        ]);
        $this->assertEquals('Bar', $element->getLabel());
        $element->setLabel('Baz');
        $this->assertEquals('Baz', $element->getLabel());
    }

    public function test_setFormDefaults_1_1()
    {
        $form = new Form();
        $form->setDefaults([
            'Foo' => [
                'first_string',
                'second_string'
            ]
        ]);

        $element = $form->text('Foo[]', 'Bar');
        $this->assertEquals('first_string', $element->getAttr('value')->getValueString());
    }



}
