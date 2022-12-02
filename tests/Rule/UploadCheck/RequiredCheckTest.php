<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule\UploadCheck;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Rule\Upload;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use Tests\Enjoys\Forms\_TestCase;

class RequiredCheckTest extends _TestCase
{

    use Reflection;

    public function test_checkRequired()
    {
        $fileElement = new File('foo');

        $request = new ServerRequest(uploadedFiles: [
            'foo' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => 0
            ])
        ], parsedBody: [], method: 'post');

        $uploadRule = new Upload([
            'required'
        ]);
        // $uploadRule->setRequest($request);

        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertSame(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getUploadedFiles()['foo'],
                $fileElement
            ])
        );
    }

    public function test_checkRequired2()
    {
        $fileElement = new File('foo');

        $uploadRule = new Upload([
            'required'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                false,
                $fileElement
            ])
        );
        $this->assertEquals('Выберите файл для загрузки', $fileElement->getRuleErrorMessage());
    }

    public function test_checkRequired3()
    {
        $fileElement = new File('foo');
        $request = new ServerRequest(uploadedFiles: [
            'foo' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => \UPLOAD_ERR_NO_FILE
            ])
        ], parsedBody: [], method: 'post');


        $uploadRule = new Upload([
            'required' => 'no file selected'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getUploadedFiles()['foo'],
                $fileElement
            ])
        );
        $this->assertEquals('no file selected', $fileElement->getRuleErrorMessage());
    }

}
