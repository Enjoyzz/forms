<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Option;


class OptionTest
{
    public function test_baseHtml()
    {
        $option = new Option('foo', 'bar');
        $this->assertSame('<option id="foo" value="foo">bar</option>', $option->baseHtml());
    }
}
