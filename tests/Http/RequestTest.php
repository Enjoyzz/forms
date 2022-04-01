<?php

namespace Tests\Enjoys\Forms\Http;

use Enjoys\Http\ServerRequest;
use HttpSoft\ServerRequest\ServerRequestCreator;
use Psr\Http\Message\UploadedFileInterface;


class RequestTest
{

    public function test_get()
    {


        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        null,
                        null,
                        [
                            'foo' => 'bar'
                        ],
                        []
                )
        );
        $this->assertEquals(['foo' => 'bar'], $request->get());
        $this->assertEquals('bar', $request->get('foo'));
        $this->assertEquals(null, $request->get('notisset'));
        $this->assertEquals('baz', $request->get('notisset', 'baz'));
    }

    public function test_post()
    {

        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        null,
                        null,
                        [],
                        [
                            'foo' => 'bar'
                        ],
                )
        );
        $this->assertEquals(['foo' => 'bar'], $request->post());
        $this->assertEquals('bar', $request->post('foo'));
        $this->assertEquals(null, $request->post('notisset'));
        $this->assertEquals('baz', $request->post('notisset', 'baz'));
    }

    public function test_files()
    {
        //$uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
        //$request = new \Enjoys\Forms\Http\Request([], [], [], [], ['food' => $uploadFile]);
       // var_dump($_FILES);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'food' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );

      //  var_dump($request->files());
        $this->assertEquals(null, $request->files('food4'));
        $this->assertInstanceOf(UploadedFileInterface::class, $request->files('food'));
        $this->assertInstanceOf(UploadedFileInterface::class, $request->files()['food']);
    }

    /**
     * этот тест надо перенести в проект functions
     * можно оставить  еще и тут , что бы наблюдать за этой фунцией и отсюда
     * @dataProvider dataStringValueForSetDefault
     */
    public function test_getValueByIndexPath($indexPath, $expect)
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
