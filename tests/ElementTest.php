<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Element;
use Enjoys\Forms\Forms;
use PHPUnit\Framework\TestCase;


class ElementTest extends TestCase
{
//    use Reflection;


    public function testSetForm()
    {
        $this->markTestIncomplete();
        $element = $this->getMockForAbstractClass(Element::class, [
            'name' => 'Foo'
        ]);
    }

    public function testGetForm()
    {
        $this->markTestSkipped();
    }

    public function testSetRequestWrapper()
    {
        $this->markTestSkipped();
    }

    public function testGetRequestWrapper()
    {
        $this->markTestSkipped();
    }

    public function testUnsetForm()
    {
        $this->markTestSkipped();
    }

    public function testPrepare()
    {
        $this->markTestSkipped();
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

    public function testSetDefault()
    {
        $this->markTestSkipped();
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

//
//
//    protected Form $form;
//
//    protected function setUp(): void
//    {
//        $this->form = new Form();
//        if (isset($this->form->getElements()[Form::_TOKEN_SUBMIT_])) {
//            $this->form->removeElement($this->form->getElements()[Form::_TOKEN_SUBMIT_]);
//        }
//
//        if (isset($this->form->getElements()[Form::_TOKEN_CSRF_])) {
//            $this->form->removeElement($this->form->getElements()[Form::_TOKEN_CSRF_]);
//        }
//    }
//
//    protected function tearDown(): void
//    {
//        unset($this->form);
//    }
//
//    public function testSetName()
//    {
//        $element = $this->getMockForAbstractClass(Element::class, [
//            'name' => 'Foo'
//        ]);
//        $this->assertEquals('Foo', $element->getName());
//    }
//
//    public function testSetId()
//    {
//        $element = $this->getMockForAbstractClass(Element::class, [
//            'name' => 'Foo'
//        ]);
//        $this->assertSame('Foo', $element->getAttr('id')->getValueString());
//        $element->setAttr(AttributeFactory::create('id', 'Baz'));
//        $this->assertSame('Baz', $element->getAttr('id')->getValueString());
//    }
//
//    public function test_getType_1_0()
//    {
//        $element = $this->getMockBuilder(Element::class)->setConstructorArgs([
//            'name' => 'Foo'
//        ])->onlyMethods(['getType'])->getMock();
//
//        $element->expects($this->once())
//            ->method('getType')
//            ->willReturn('option');
//
//        $this->assertEquals('option', $element->getType());
//
//
////        $text = $this->form->text('Bar');
////        $this->assertEquals('text', $text->getType());
//    }
//
//    public function testSetLabel()
//    {
//        $element = $this->getMockForAbstractClass(Element::class, [
//            'name' => 'Foo',
//            'label' => 'Bar'
//        ]);
//        $this->assertEquals('Bar', $element->getLabel());
//        $element->setLabel('Baz');
//        $this->assertEquals('Baz', $element->getLabel());
//    }
//
//    public function test_setFormDefaults_1_1()
//    {
//        $this->form->setDefaults([
//            'Foo' => [
//                'first_string',
//                'second_string'
//            ]
//        ]);
//
//        $element = $this->form->text('Foo[]', 'Bar');
//        $this->assertEquals('first_string', $element->getAttr('value')->getValueString());
//    }
//


}
