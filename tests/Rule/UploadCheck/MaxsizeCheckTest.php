<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule\UploadCheck;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Rule\Upload;
use Enjoys\Forms\Rule\UploadCheck\MaxsizeCheck;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use Psr\Http\Message\UploadedFileInterface;
use Tests\Enjoys\Forms\_TestCase;

class MaxsizeCheckTest extends _TestCase
{

    use Reflection;

    public function testCheckMaxsize()
    {

        $fileElement = new File('foo');

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [
                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ])
            ], parsedBody: [], method: 'post')
        );

        $uploadRule = new MaxsizeCheck($request->getFilesData('foo'), $fileElement, 999);


        $this->assertEquals(
            false,
            $uploadRule->check()
        );

        $this->assertSame(
            "Размер файла (1000 B) превышает допустимый размер: 999 B",
            $fileElement->getRuleErrorMessage()
        );
    }

    public function test_checkMaxsize2()
    {

        $fileElement = new File('foo');

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ])

            ])
        );

        $uploadRule = new MaxsizeCheck($request->getFilesData('foo'), $fileElement, 1000);

        $this->assertEquals(
            true,
            $uploadRule->check()
        );
    }

    public function test_checkMaxsize3()
    {

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ])

            ])
        );

        $fileElement = new File('foo');
        $uploadRule = new MaxsizeCheck($request->getFilesData('foo'), $fileElement, 10, 'big file');
        $this->assertEquals(
            false,
            $uploadRule->check()
        );
        $this->assertEquals('big file', $fileElement->getRuleErrorMessage());
    }

    public function test_checkMaxsize4()
    {
        $fileElement = new File('foo');
        $uploadRule = new MaxsizeCheck(false, $fileElement, '10');
        $this->assertEquals(
            true,
            $uploadRule->check()
        );
    }

    public function testCheckMaxsizeInvalidThresholdParam()
    {
        $this->expectError();
        $uploadRule = new MaxsizeCheck(
            UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => 0
            ]),
            new File('foo'),
            'non numeric'
        );
        $uploadRule->check();
    }

    public function testCheckMaxsizeIfIncorrectUploadFileSizeNull()
    {
        $fileElement = new File('foo');

        $fileMock = $this->getMockBuilder(UploadedFileInterface::class)->getMock();
        $fileMock->expects($this->any())->method('getSize')->willReturn(null);
        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [
                'foo' => $fileMock
            ])
        );

        $uploadRule = new Upload(null, [
            'maxsize' => 0
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getFilesData('foo'),
                $fileElement
            ])
        );
    }
}
