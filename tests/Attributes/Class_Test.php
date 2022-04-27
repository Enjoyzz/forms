<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Attributes;

use Enjoys\Forms\Attributes\Class_;
use Tests\Enjoys\Forms\_TestCase;

class Class_Test extends _TestCase
{
    public function testRenderIfNotAddedValue()
    {
        $attr = new Class_();
        $this->assertSame('', $attr->__toString());
    }

    public function testRenderIfAddedValue()
    {
        $attr = new Class_();
        $attr->set([
            'test',
            'test2'
        ]);
        $this->assertSame('class="test test2"', $attr->__toString());
    }
}