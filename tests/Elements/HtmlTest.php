<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Html;

class HtmlTest
{
    public function test_basehtml()
    {
        $obj = new Html('<b></b>');
        $this->assertSame('<b></b>', $obj->baseHtml());
    }
}
