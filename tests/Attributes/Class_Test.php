<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Attributes;

use Enjoys\Forms\Attributes\ClassAttribute;
use Tests\Enjoys\Forms\_TestCase;

class Class_Test extends _TestCase
{
    public function testRenderIfNotAddedValue()
    {
        $attr = new ClassAttribute();
        $this->assertSame('', $attr->__toString());
    }

    public function testRenderIfAddedValue()
    {
        $attr = new ClassAttribute();
        $attr->set([
            'test',
            'test2'
        ]);
        $this->assertSame('class="test test2"', $attr->__toString());
    }
}
