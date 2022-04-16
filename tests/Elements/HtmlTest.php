<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Html;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{
    public function test_basehtml()
    {
        $el = new Html('<b></b>');
        $this->assertSame('<b></b>', $el->baseHtml());
        $this->assertNull($el->getAttr('id'));
        $this->assertNull($el->getAttr('name'));
    }
}
