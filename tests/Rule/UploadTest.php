<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
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

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\Upload;
use Enjoys\Http\ServerRequest;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;
use PHPUnit\Framework\TestCase;
use Tests\Enjoys\Forms\Reflection;

/**
 * Description of UploadTest
 *
 * @author deadl
 */
class UploadTest extends TestCase
{
    use Reflection;

    public function test_validate_uploadrule()
    {
        $fileElement = new File('foo');

        //$uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);

        $request = new ServerRequestWrapper(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );

//        $requestMock = $this->createMock(\Enjoys\Forms\Http\Request::class);
//        $requestMock->expects($this->any())->method('files')->will($this->returnCallback(fn() => $request->files()));
        $uploadRule = new Upload(null, [
            'required'
        ]);
        $uploadRule->setRequestWrapper($request);
        $this->assertEquals(true, $uploadRule->validate($fileElement));
    }

    public function test_validate_uploadrule2()
    {
        $fileElement = new File('foo');
        $request = new ServerRequestWrapper(
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
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
//        $requestMock = $this->createMock(\Enjoys\Forms\Http\Request::class);
//        $requestMock->expects($this->any())->method('files')->will($this->returnCallback(fn() => ['food' => $uploadFile]));
        $uploadRule = new Upload(null, [
            'required'
        ]);
        $uploadRule->setRequestWrapper($request);
        $this->assertEquals(false, $uploadRule->validate($fileElement));
    }

    public function test_checkRequired()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'required'
        ]);
        // $uploadRule->setRequest($request);

        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertSame(true, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkRequired2()
    {
        $fileElement = new File('foo');

        $uploadRule = new Upload(null, [
            'required'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                    false, $fileElement
        ]));
    }

    public function test_checkRequired3()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', \UPLOAD_ERR_NO_FILE, true);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => \UPLOAD_ERR_NO_FILE
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'required' => 'no file selected'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
        $this->assertEquals('no file selected', $fileElement->getRuleErrorMessage());
    }

    public function test_checkMaxsize()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'maxsize' => 999
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkMaxsize2()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'maxsize' => 250000
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkMaxsize3()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'maxsize' => [
                '10',
                'big file'
            ]
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
        $this->assertEquals('big file', $fileElement->getRuleErrorMessage());
    }

    public function test_checkMaxsize4()
    {
        $fileElement = new File('foo');
        $uploadRule = new Upload(null, [
            'maxsize' => [
                '10'
            ]
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    false, $fileElement
        ]));
    }

    public function test_checkExtensions()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'extensions' => [
                'doc, jpg',
                'not support'
            ]
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                     $request->files('foo'), $fileElement
        ]));
        $this->assertEquals('not support', $fileElement->getRuleErrorMessage());
    }

    public function test_checkExtensions2()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
                $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'extensions' => [
                'doc, jpg, pdf',
                'not support'
            ]
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkExtensions3()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
                $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => 0
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'extensions' => 'doc'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkExtensions4()
    {
        $fileElement = new File('foo');
        //$uploadFile = false;
        $uploadRule = new Upload(null, [
            'extensions' => [
                'doc, jpg',
                'not support'
            ]
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    false, $fileElement
        ]));
    }

    public function test_checkSystem()
    {
        $fileElement = new File('foo');
        //$uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', \UPLOAD_ERR_OK, true);
                $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => \UPLOAD_ERR_OK
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkSystem2()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', \UPLOAD_ERR_NO_FILE, true);
                        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => \UPLOAD_ERR_NO_FILE
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));
    }

    public function test_checkSystem3()
    {
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', \UPLOAD_ERR_FORM_SIZE, true);
         $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => \UPLOAD_ERR_FORM_SIZE
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(false, $testedMethod->invokeArgs($uploadRule, [
                    $request->files('foo'), $fileElement
        ]));

        $privateProperty = $this->getPrivateProperty(Upload::class, 'systemErrorMessage');
        $this->assertEquals($privateProperty->getValue($uploadRule)[\UPLOAD_ERR_FORM_SIZE], $fileElement->getRuleErrorMessage());
    }

    public function test_checkSystem4()
    {
        $fileElement = new File('foo');
//        $uploadFile = false;
        $uploadRule = new Upload(null, [
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(true, $testedMethod->invokeArgs($uploadRule, [
                    false, $fileElement
        ]));
    }

    public function test_checkUnknown()
    {
        $this->expectException(ExceptionRule::class);
        $fileElement = new File('foo');
//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', \UPLOAD_ERR_NO_FILE, true);
         $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        null,
                        [
                            'foo' => [
                                'name' => 'test.pdf',
                                'type' => 'application/pdf',
                                'size' => 1000,
                                'tmp_name' => 'test.pdf',
                                'error' => \UPLOAD_ERR_NO_FILE
                            ]
                        ]
                )
        );
        $uploadRule = new Upload(null, [
            'unknown'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $testedMethod->invokeArgs($uploadRule, [$request->files('foo'), $fileElement]);
    }
}
