<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Option;
use PHPUnit\Framework\TestCase;


class OptionTest extends TestCase
{
    public function testInitElement()
    {
        $el = new Option('foo');
        $this->assertNull($el->getAttr('name'));
        $this->assertSame('foo', $el->getAttr('value')->getValueString());
    }
    public function test_baseHtml()
    {
        $option = new Option('foo', 'bar');
        $this->assertSame('<option value="foo">bar</option>', $option->baseHtml());

    }
}
