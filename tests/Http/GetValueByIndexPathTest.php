<?php

namespace Tests\Enjoys\Forms\Http;

use PHPUnit\Framework\TestCase;

class GetValueByIndexPathTest extends TestCase
{
    /**
     * этот тест надо перенести в проект functions
     * можно оставить  еще и тут , что бы наблюдать за этой фунцией и отсюда
     * @dataProvider dataStringValueForSetDefault
     */
    public function testGetValueByIndexPath($indexPath, $expect)
    {

        $arrays = [
            'foo' => [
                'bar' => 'bar1',
                'name' => 'myname',
                4,
                'test' => [
                    '3' => 55
                ]
            ],
            'notarray' => 'yahoo',
            'arrays' => [
            ],
            'fff' => [
                1, 2
            ],
            'test' => [
                [
                    [
                        25, 11
                    ]
                ]
            ],
            'bar' => 'test',
            'bars' => ['test'],
            'baz' => [
                [
                    [
                        'ddd'
                    ]
                ]
            ]
        ];
        $this->assertEquals($expect, \getValueByIndexPath($indexPath, $arrays));
    }

    public function dataStringValueForSetDefault()
    {
        return [
            ['/invalide_string/', false],
            ['//', false],
            ['', false],
            ['invalide', false],
            ['invalide[]', false],
            ['invalide[][]', false],
            ['invalide[invalide][]', false],
            ['foo[bar]', 'bar1'],
            ['foo[name]', 'myname'],
            ['foo[0]', 4],
            ['notarray', 'yahoo'],
            ['notarray[]', false],
            ['fff[]', [1, 2]], //11
            ['fff[0]', 1], //11
            ['foo[test][3]', 55],
            ['foo[test][3]', '55'],
            ['arrays', []],
            ['bar', 'test'],
            ['bar[]', false],
            ['bar[][]', false],
            ['bars', ['test']],
            ['bars[]', 'test'],
            ['bars[][]', false],
            ['baz', [[['ddd']]]],
            ['baz[]', false],
            ['baz[][]', false],
            ['baz[][][]', 'ddd'],
            ['test[][][0]', 25],
            ['fff', [1, 2]],
            ['foo[]', [
                    'bar' => 'bar1',
                    'name' => 'myname',
                    4,
                    'test' => [
                        '3' => 55
                    ]
                ]], //15
            ['test[][][]', [
                    25, 11
                ]], //16
        ];
    }
}
