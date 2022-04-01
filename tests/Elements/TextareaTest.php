<?php


namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Textarea;
use Enjoys\Forms\Form;

class TextareaTest
{

    public function test_init_textarea()
    {
        $el = new Textarea('foo', 'title1');
        $this->assertTrue($el instanceof Textarea);
        $this->assertEquals('foo', $el->getName());
//        $this->assertEquals('foo', $el->getValidateName());
        $this->assertEquals('title1', $el->getLabel());
        $el->setValue('text');
        $this->assertEquals('text', $el->getValue());
        $el->setValue('text2');
        $this->assertEquals('text2', $el->getValue());
        $el->setAttrs(Attribute::createFromArray(['class' => 'textarea_class']));
        $this->assertEquals('textarea_class', $el->getAttr('class')->getValueString());
//        $this->assertEquals(['textarea_class'], $el->getClassesList());
        $el->addClass('textarea_class2');
        $this->assertEquals('textarea_class textarea_class2', $el->getAttr('class')->getValueString());
    }

    public function test_setCols()
    {
        $el = new Textarea('foo');
        $el->setCols(5);
        $this->assertEquals('5', $el->getAttr('cols')->getValueString());
        $el->setCols('25');
        $this->assertEquals('25', $el->getAttr('cols')->getValueString());
    }

    public function test_setRows()
    {
        $el = new Textarea('foo');
        $el->setRows(50);
        $this->assertEquals('50', $el->getAttr('rows')->getValueString());
        $el->setRows('250');
        $this->assertEquals('250', $el->getAttr('rows')->getValueString());
    }

    public function test_basehtml()
    {
        $el = (new Textarea('foo', 'bar'))->setValue('text2');
        $this->assertEquals('<textarea id="foo" name="foo">text2</textarea>', $el->baseHtml());
    }

    public function test_basehtml2()
    {
        $form = new Form();
        $form->setDefaults([
            'foo' => 'baz'
        ]);
        $el = $form->textarea('foo');
        $this->assertEquals('<textarea id="foo" name="foo">baz</textarea>', $el->baseHtml());
    }
}
