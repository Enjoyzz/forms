<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\FillHandler;
use PHPUnit\Framework\TestCase;

class FillHandlerTest extends TestCase
{

    /**
     * @dataProvider data
     */
    public function testFillHandler($value, $label, $titleAsValue, $expectV, $expectL, $expectAttr)
    {
        if ($titleAsValue) {
            $handler = new FillHandler($value, $label, $titleAsValue);
        } else {
            $handler = new FillHandler($value, $label);
        }
        $this->assertEquals($expectV, $handler->getValue());
        $this->assertEquals($expectL, $handler->getLabel());
        $this->assertEquals($expectAttr, $handler->getAttributes());
    }

    public function data()
    {
        return [
            [0, 1, true, '1', '1', []],
            [0, 1, false, '0', '1', []],
            [' 0', 1, true, '0', '1', []],
            [0, [1, 2], false, '0', '1', []],
            [0, [1 => [2]], false, '0', '', [2]],
            [0, [1, ['id' => 'test']], true, '1', '1', ['id' => 'test']],
            [0, [1, ['test']], true, '1', '1', [0 => 'test']],
        ];
    }

}
