<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Textarea;
use Enjoys\Forms\Form;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class TextareaTest extends TestCase
{
    public function testTextareaInit()
    {
        $el = new Textarea('foo', 'title1');
        $this->assertEquals('textarea', $el->getType());
    }

    public function testGetValue()
    {
        $el = (new Textarea('foo', 'bar'))->setValue('<b>text</b>');
        $this->assertSame('<b>text</b>', $el->getValue());
    }

    public function testSetCols()
    {
        $el = new Textarea('foo');
        $el->setCols(5);
        $this->assertEquals('5', $el->getAttribute('cols')->getValueString());
        $el->setCols(25);
        $this->assertEquals('25', $el->getAttribute('cols')->getValueString());
        $el->setCols(function () {
            return 1;
        });
        $this->assertEquals('1', $el->getAttribute('cols')->getValueString());
    }

    public function testSetRows()
    {
        $el = new Textarea('foo');
        $el->setRows(50);
        $this->assertEquals('50', $el->getAttribute('rows')->getValueString());
        $el->setRows(250);
        $this->assertEquals('250', $el->getAttribute('rows')->getValueString());
        $el->setRows(function () {
            return 1;
        });
        $this->assertEquals('1', $el->getAttribute('rows')->getValueString());
    }

    public function testInvalidSetCols()
    {
        $this->expectError();
        (new Textarea('foo'))->setCols('not number');
    }

    public function testInvalidSetRows()
    {
      $this->expectException(InvalidArgumentException::class);
        (new Textarea('foo'))->setCols(function (){
            return 'not number';
        });

    }


    public function testBaseHtml()
    {
        $el = (new Textarea('foo', 'bar'))->setValue('text2');
        $this->assertEquals('<textarea id="foo" name="foo">text2</textarea>', $el->baseHtml());
    }

    public function testBaseHtmlWithDefaultValue()
    {

        $form = new Form();
        $form->setDefaults([
            'foo' => 'baz'
        ]);
        $el = $form->textarea('foo');
        $this->assertEquals('<textarea id="foo" name="foo">baz</textarea>', $el->baseHtml());
    }
}
