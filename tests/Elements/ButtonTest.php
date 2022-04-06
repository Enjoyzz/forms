<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Button;
use PHPUnit\Framework\TestCase;


class ButtonTest extends TestCase
{
    public function test_baseHtml()
    {
        $btn = new Button('foo', 'bar');
        $this->assertEquals('<button id="foo" name="foo">bar</button>', $btn->baseHtml());
    }
}
