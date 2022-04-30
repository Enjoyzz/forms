<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Elements\Header;
use Enjoys\Forms\Form;
use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    public function testInitElement()
    {
        $el = new Header('title');
        $this->assertSame('title', $el->getLabel());
        $this->assertNull($el->getAttribute('id'));
        $this->assertNull($el->getAttribute('name'));
    }

    public function test_attr_legend()
    {
        $obj = new Header('title');
        $obj->setAttribute(AttributeFactory::create('id', 'test'));
        $this->assertSame('test', $obj->getAttribute('id')->getValueString());
    }

    public function test_attr_fieldset()
    {
        $obj = new Header('title');
        $obj->setAttributes(
            AttributeFactory::createFromArray([
                'id' => 'test'
            ]),
            Form::ATTRIBUTES_FIELDSET
        );
        $this->assertSame('test', $obj->getAttribute('id', Form::ATTRIBUTES_FIELDSET)->getValueString());
    }

    public function test_attr_fieldset_get()
    {
        $obj = new Header('title');
        $obj->setAttributes(AttributeFactory::createFromArray([
            'id' => 'test',
            'disabled' => null
        ]), Form::ATTRIBUTES_FIELDSET);
        $this->assertSame(' id="test" disabled', $obj->getAttributesString(Form::ATTRIBUTES_FIELDSET));
    }

    public function test_close_after()
    {
        $obj = new Header('title');
        $obj->closeAfter(5);
        $this->assertSame(5, $obj->getCloseAfterCountElements());
    }

    public function test_basehtml()
    {
        $obj = new Header('title');
        $this->assertSame('title', $obj->baseHtml());
    }
}
