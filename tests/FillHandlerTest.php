<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\FillHandler;

class FillHandlerTest
{

    /**
     * @dataProvider data
     */
    public function test_fillhandler($value, $title, $expectV, $expectL, $expectAttr)
    {
        $handler = new FillHandler($value, $title, true);
        $this->assertEquals($expectV, $handler->getValue());
        $this->assertEquals($expectL, $handler->getLabel());
        $this->assertEquals($expectAttr, $handler->getAttributes());
    }

    public function data()
    {
        return [
            [0, 1, '1', '1', []],
            [' 0', 1, '0', '1', []],
            [0, [1, ['id' => 'test']], '1', '1', ['id' => 'test']],
            [0, [1, ['test']], '1', '1', [0 => 'test']],
        ];
    }
}
