<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Attributes;

use Enjoys\Forms\Attributes\IdHtmlAttribute;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function testRenderIfNotAddedValue()
    {
        $attr = new IdHtmlAttribute();
        $this->assertSame('', $attr->__toString());
    }

    public function testRenderIfAddedValue()
    {
        $attr = new IdHtmlAttribute();
        $attr->set([
            'test',
            'test2'
        ]);
        $this->assertSame('id="test2"', $attr->__toString());
    }
}
