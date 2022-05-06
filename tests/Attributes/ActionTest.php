<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Attributes;

use Enjoys\Forms\Attributes\ActionAttribute;
use Tests\Enjoys\Forms\_TestCase;

class ActionTest extends _TestCase
{
    public function testRenderIfNotAddedValue()
    {
        $attr = new ActionAttribute();
        $this->assertSame('', $attr->__toString());
    }

    public function testRenderIfAddedValue()
    {
        $attr = new ActionAttribute();
        $attr->add('test');
        $this->assertSame('action="test"', $attr->__toString());
    }
}
