<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Attributes;

use Enjoys\Forms\Attributes\ActionHtmlAttribute;
use Tests\Enjoys\Forms\_TestCase;

class ActionTest extends _TestCase
{
    public function testRenderIfNotAddedValue()
    {
        $attr = new ActionHtmlAttribute();
        $this->assertSame('', $attr->__toString());
    }

    public function testRenderIfAddedValue()
    {
        $attr = new ActionHtmlAttribute();
        $attr->add('test');
        $this->assertSame('action="test"', $attr->__toString());
    }
}
