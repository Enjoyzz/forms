<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Reset;
use PHPUnit\Framework\TestCase;


class ResetTest extends TestCase
{

    public function test_init()
    {
        $el = new Reset('foo', 'title1');
        $this->assertTrue($el instanceof Reset);
        $this->assertEquals('title1', $el->getAttr('value')->getValueString());
    }
}
