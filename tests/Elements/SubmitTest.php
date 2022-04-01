<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Submit;
use PHPUnit\Framework\TestCase;


class SubmitTest extends TestCase
{

    public function test_init()
    {
        $el = new Submit('foo', 'title1');
        $this->assertEquals('title1', $el->getAttr('value')->getValueString());
    }
}
