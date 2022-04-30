<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Option;
use Enjoys\Forms\Elements\Select;
use Enjoys\Traits\Reflection;
use Tests\Enjoys\Forms\_TestCase;

class OptionTest extends _TestCase
{
    use Reflection;

    public function testInitElement()
    {
        $el = new Option('foo');
        $this->assertNull($el->getAttribute('name'));
        $this->assertSame('foo', $el->getAttribute('value')->getValueString());
    }

    public function test_baseHtml()
    {
        $option = new Option('foo', 'bar');
        $this->assertSame('<option value="foo">bar</option>', $option->baseHtml());
    }

    public function testOptionWithSelect()
    {
        $el = new Select('foo');
        $el->fill([
            'value1' => [
                'label1',
                [
                    'class' => 'red'
                ]
            ]
        ]);
        $option = current($el->getElements());
        $this->assertSame('<option value="value1" class="red">label1</option>', $option->baseHtml());
    }

    public function testSetDefaultWithArray()
    {
        $method = $this->getPrivateMethod(Option::class, 'setDefault');

        $option = new Option('value1', 'label1');
        $method->invokeArgs($option, [['value1']]);
        $this->assertSame('<option value="value1" selected>label1</option>', $option->baseHtml());

        $option = new Option('value1', 'label1');
        $option->removeAttribute('value');
        $method->invokeArgs($option, [['value1']]);
        $this->assertSame('<option>label1</option>', $option->baseHtml());
    }

    public function testSetDefaultWithString()
    {
        $method = $this->getPrivateMethod(Option::class, 'setDefault');

        $option = new Option('value1', 'label1');
        $method->invokeArgs($option, ['value1']);
        $this->assertSame('<option value="value1" selected>label1</option>', $option->baseHtml());

        $option = new Option('value1', 'label1');
        $option->removeAttribute('value');
        $method->invokeArgs($option, ['value1']);
        $this->assertSame('<option>label1</option>', $option->baseHtml());
    }
}
