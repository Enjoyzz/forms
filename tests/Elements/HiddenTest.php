<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Forms;


class HiddenTest
{

    /**
     * @dataProvider dataForConstruct
     */
    public function test_full_construct($name, $value, $expectName, $expectValue)
    {
//        $this->markTestSkipped('Проверить тест');
        $obj = new Hidden($name, $value);
        $this->assertSame($expectName, $obj->getAttr('name')->getValueString());
        $this->assertSame($expectValue, $obj->getAttr('value')->getValueString());
        $this->assertSame(null, $obj->getAttr('id')?->getValueString());
    }

    public function dataForConstruct()
    {
        return [
            ['name', 'value', 'name', 'value'],
            ['name', null, 'name', '']
        ];
    }
}
