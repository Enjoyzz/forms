<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Attributes;

use Enjoys\Forms\Attributes\Id;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function testRenderIfNotAddedValue()
    {
        $attr = new Id();
        $this->assertSame('', $attr->__toString());
    }

    public function testRenderIfAddedValue()
    {
        $attr = new Id();
        $attr->set([
            'test',
            'test2'
        ]);
        $this->assertSame('id="test2"', $attr->__toString());
    }
}
