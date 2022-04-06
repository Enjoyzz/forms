<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Html;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{
    public function test_basehtml()
    {
        $obj = new Html('<b></b>');
        $this->assertSame('<b></b>', $obj->baseHtml());
    }
}
