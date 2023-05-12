<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule\UploadCheck;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Rule\Upload;
use Enjoys\Forms\Rule\UploadCheck\UploadCheckInterface;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use Tests\Enjoys\Forms\_TestCase;
use Tests\Enjoys\Forms\Reflection;

class SystemCheckTest extends _TestCase
{
    use Reflection;

    public function test_checkSystem()
    {
        $fileElement = new File('foo');

        $request = new ServerRequest(uploadedFiles: [

            'foo' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => \UPLOAD_ERR_OK
            ])

        ]);
        $uploadRule = new Upload([
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');

        $this->assertEquals(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getUploadedFiles()['foo'],
                $fileElement
            ])
        );
    }

    public function test_checkSystem2()
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

        ]);
        $uploadRule = new Upload([
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getUploadedFiles()['foo'],
                $fileElement
            ])
        );
    }

    public function test_checkSystem3()
    {
        $fileElement = new File('foo');

        $request = new ServerRequest(uploadedFiles: [

            'foo' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => \UPLOAD_ERR_FORM_SIZE
            ])

        ]);
        $uploadRule = new Upload([
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getUploadedFiles()['foo'],
                $fileElement
            ])
        );


        $this->assertEquals(
            UploadCheckInterface::UPLOAD_ERROR_MESSAGE[UPLOAD_ERR_FORM_SIZE],
            $fileElement->getRuleErrorMessage()
        );
    }

    public function test_checkSystem4()
    {
        $fileElement = new File('foo');
//        $uploadFile = false;
        $uploadRule = new Upload([
            'system'
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
