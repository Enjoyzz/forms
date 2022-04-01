<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Submit;


class SubmitTest
{

    public function test_init()
    {
        $el = new Submit('foo', 'title1');
        $this->assertEquals('title1', $el->getAttr('value')->getValueString());
    }
}
