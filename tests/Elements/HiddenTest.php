<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Hidden;
use PHPUnit\Framework\TestCase;

class HiddenTest extends TestCase
{
    /**
     * @dataProvider dataForConstruct
     */
    public function test_full_construct($name, $value, $expectName, $expectValue)
    {
//        $this->markTestSkipped('Проверить тест');
        $obj = new Hidden($name, $value);
        $this->assertSame($expectName, $obj->getAttribute('name')->getValueString());
        $this->assertSame($expectValue, $obj->getAttribute('value')->__toString());
        $this->assertSame(null, $obj->getAttribute('id')?->getValueString());
    }

    public function dataForConstruct()
    {
        return [
            ['name', 'value', 'name', 'value="value"'],
            ['name', null, 'name', '']
        ];
    }
}
