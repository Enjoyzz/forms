<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule\UploadCheck;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Rule\Upload;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use Tests\Enjoys\Forms\_TestCase;

class ExtensionsCheckTest extends _TestCase
{

    use Reflection;

    public function test_checkExtensions()
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
        $uploadRule = new Upload(null, [
            'extensions' => [
                'doc, jpg',
                'not support'
            ]
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getFilesData('foo'),
                $fileElement
            ])
        );
        $this->assertEquals('not support', $fileElement->getRuleErrorMessage());
    }

    public function test_checkExtensions2()
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
        $uploadRule = new Upload(null, [
            'extensions' => [
                'doc, jpg, pdf',
                'not support'
            ]
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

    public function test_checkExtensions3()
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
        $uploadRule = new Upload(null, [
            'extensions' => 'doc'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getFilesData('foo'),
                $fileElement
            ])
        );
        $this->assertSame("Загрузка файлов с расширением .pdf запрещена", $fileElement->getRuleErrorMessage());
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
        $this->assertEquals(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                false,
                $fileElement
            ])
        );
    }

}
