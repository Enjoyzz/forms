<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\AttributeFactory;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class AttributeFactoryTest extends TestCase
{

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
                    'Class' => 'test case'
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
}
