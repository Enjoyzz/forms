<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Tests\Enjoys\Forms\Http;

/**
 * Class RequestTest
 *
 * @author Enjoys
 */
class RequestTest 
{

    public function test_get()
    {
        $request = new \Enjoys\Forms\Http\Request([
            'foo' => 'bar'
                ], []);
        $this->assertEquals(['foo' => 'bar'], $request->get());
        $this->assertEquals('bar', $request->get('foo'));
        $this->assertEquals(null, $request->get('notisset'));
        $this->assertEquals('baz', $request->get('notisset', 'baz'));
    }

    public function test_post()
    {
        $request = new \Enjoys\Forms\Http\Request([], [
            'foo' => 'bar'
        ]);
        $this->assertEquals(['foo' => 'bar'], $request->post());
        $this->assertEquals('bar', $request->post('foo'));
        $this->assertEquals(null, $request->post('notisset'));
        $this->assertEquals('baz', $request->post('notisset', 'baz'));
    }
    
//    public function test_files()
//    {
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
//        $requestMock = $this->createMock(\Enjoys\Forms\Http\Request::class);
//        $requestMock->expects($this->any())->method('files')->will($this->returnCallback(fn() => ['food' => $uploadFile]));
//         $this->assertInstanceOf('\Symfony\Component\HttpFoundation\File\UploadedFile', $requestMock->files('food'));  
//    }

    /**
     * @dataProvider dataStringValueForSetDefault
     */
    public function test_getValueByIndexPath($indexPath, $expect)
    {
        $this->markTestIncomplete();
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
        $this->assertEquals($expect, \Enjoys\Forms\Http\Request::getValueByIndexPath($indexPath, $arrays));
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
            ['fff', [1,2]],
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
