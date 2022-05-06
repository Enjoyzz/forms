<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Attributes\Base;
use Enjoys\Traits\Reflection;
use Webmozart\Assert\InvalidArgumentException;

class AttributeFactoryTest extends _TestCase
{
    use Reflection;

    public function dataForCreateFromArray()
    {
        return [
            //array, expect, exception
            [
                [
                    'test' => 'string_value'
                ],
                [
                    [
                        'name' => 'test',
                        'value' => ['string_value']
                    ]
                ],
                null
            ],
            [
                [
                    'Id' => null,
                    'ClAsS' => 'test case'
                ],
                [
                    [
                        'name' => 'id',
                        'value' => []
                    ],
                    [
                        'name' => 'class',
                        'value' => ['test', 'case']
                    ]
                ],
                null
            ],
            [
                [
                    'single',
                    'id' => null,
                    'class' => 'test case',
                    function () {
                        return 'id';
                    },
                ],
                [
                    null
                ],
                InvalidArgumentException::class
            ]
        ];
    }

    /**
     * @dataProvider dataForCreateFromArray
     */
    public function testCreateFromArray($input, $expect, $exception)
    {
        if ($exception !== null) {
            $this->expectException($exception);
        }
        $attrs = AttributeFactory::createFromArray($input);
        foreach ($attrs as $key => $attr) {
            $this->assertSame($expect[$key]['name'], $attr->getName());
            $this->assertSame($expect[$key]['value'], $attr->getValues());
        }
    }


    public function testGetClass()
    {
        $method = $this->getPrivateMethod(AttributeFactory::class, 'getClass');
        $result = $method->invokeArgs(null, ['fake']);
        $this->assertInstanceOf(Base::class, $result);
    }
}

namespace Enjoys\Forms\Attributes;
class FakeAttribute {}
