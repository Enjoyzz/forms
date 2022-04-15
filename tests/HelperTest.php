<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

    public function testWithNeedleString1()
    {
        $needle = 'test';
        $array = [
            'first' => [
                'test',
                '42'
            ]
        ];
        $this->assertSame(['first', 0], Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }

    public function testWithNeedleString2()
    {
        $needle = 'test';
        $array = [
            'first' => [
                '42',
                'test',
            ]
        ];
        $this->assertSame(['first', 1], Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }

    public function testWithNeedleStringInMultiArray()
    {
        $needle = 'test';
        $array = [
            'first' => [
                'second' => [
                    'third' => [
                        'fourth' => [
                            '42',
                            'test',
                        ]
                    ]
                ]
            ]
        ];
        $this->assertSame(['first', 'second', 'third', 'fourth', 1], Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }

    public function testWithNeedleArray()
    {
        $needle = [
            'test',
            '42'
        ];

        $array = [
            'first' => [
                'test',
                '42'
            ]
        ];
        $this->assertSame(['first'], Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }

    public function testWithNeedleArrayInMultiArray()
    {
        $needle = [
            '42',
            'test',
        ];

        $array = [
            'first' => [
                'second' => [
                    'third' => [
                        'fourth' => [
                            '42',
                            'test',
                        ]
                    ]
                ]
            ]
        ];
        $this->assertSame(['first', 'second', 'third', 'fourth'], Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }

    public function testIfNotFoundNeedleString()
    {
        $needle = 'test';
        $array = [
            'first' => [
                '42'
            ]
        ];
        $this->assertSame(null, Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }

    public function testIfNotFoundNeedleArray()
    {
        $needle = [
            'test',
            '42',
        ];

        $array = [
            'first' => [
                'second' => [
                    'third' => [
                        'fourth' => [
                            '42',
                            'test',
                        ]
                    ]
                ]
            ]
        ];
        $this->assertSame(null, Helper::arrayRecursiveSearchKeyMap($needle, $array));
    }
}
