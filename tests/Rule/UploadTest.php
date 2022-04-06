<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\Upload;
use Enjoys\ServerRequestWrapper;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use PHPUnit\Framework\TestCase;
use Tests\Enjoys\Forms\Reflection;


class UploadTest extends TestCase
{
    use Reflection;

    public function test_validate_uploadrule()
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

//        $requestMock = $this->createMock(\Enjoys\Forms\Http\Request::class);
//        $requestMock->expects($this->any())->method('files')->will($this->returnCallback(fn() => $request->files()));
        $uploadRule = new Upload(null, [
            'required'
        ]);
        $uploadRule->setRequest($request);
        $this->assertEquals(true, $uploadRule->validate($fileElement));
    }

    public function test_validate_uploadrule2()
    {
        $fileElement = new File('foo');
        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [
                'food' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ])
            ], parsedBody: [], method: 'post')
        );

//        $uploadFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'test.pdf', 'application/pdf', 0, true);
//        $requestMock = $this->createMock(\Enjoys\Forms\Http\Request::class);
//        $requestMock->expects($this->any())->method('files')->will($this->returnCallback(fn() => ['food' => $uploadFile]));
        $uploadRule = new Upload(null, [
            'required'
        ]);
        $uploadRule->setRequest($request);
        $this->assertEquals(false, $uploadRule->validate($fileElement));
    }

    public function test_checkRequired()
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
            'required'
        ]);
        // $uploadRule->setRequest($request);

        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertSame(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getRequest()->getUploadedFiles()['foo'],
                $fileElement
            ])
        );
    }

    public function test_checkRequired2()
    {
        $fileElement = new File('foo');

        $uploadRule = new Upload(null, [
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
    }

    public function test_checkRequired3()
    {
        $fileElement = new File('foo');
        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [
                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => \UPLOAD_ERR_NO_FILE
                ])
            ], parsedBody: [], method: 'post')
        );


        $uploadRule = new Upload(null, [
            'required' => 'no file selected'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getFilesData('foo'),
                $fileElement
            ])
        );
        $this->assertEquals('no file selected', $fileElement->getRuleErrorMessage());
    }

    public function test_checkMaxsize()
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
            'maxsize' => 999
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getFilesData('foo'),
                $fileElement
            ])
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

            ], parsedBody: [], method: 'post')
        );


        $uploadRule = new Upload(null, [
            'maxsize' => 250000
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

    public function test_checkMaxsize3()
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
            'maxsize' => [
                '10',
                'big file'
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
        $this->assertEquals(
            true,
            $testedMethod->invokeArgs($uploadRule, [
                false,
                $fileElement
            ])
        );
    }

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

    public function test_checkSystem()
    {
        $fileElement = new File('foo');

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => \UPLOAD_ERR_OK
                ])

            ], parsedBody: [], method: 'post')
        );
        $uploadRule = new Upload(null, [
            'system'
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

    public function test_checkSystem2()
    {
        $fileElement = new File('foo');

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => \UPLOAD_ERR_NO_FILE
                ])

            ], parsedBody: [], method: 'post')
        );
        $uploadRule = new Upload(null, [
            'system'
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

    public function test_checkSystem3()
    {
        $fileElement = new File('foo');

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => \UPLOAD_ERR_FORM_SIZE
                ])

            ], parsedBody: [], method: 'post')
        );
        $uploadRule = new Upload(null, [
            'system'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            false,
            $testedMethod->invokeArgs($uploadRule, [
                $request->getFilesData('foo'),
                $fileElement
            ])
        );

        $privateProperty = $this->getPrivateProperty(Upload::class, 'systemErrorMessage');
        $this->assertEquals(
            $privateProperty->getValue($uploadRule)[\UPLOAD_ERR_FORM_SIZE],
            $fileElement->getRuleErrorMessage()
        );
    }

    public function test_checkSystem4()
    {
        $fileElement = new File('foo');
//        $uploadFile = false;
        $uploadRule = new Upload(null, [
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

    public function test_checkUnknown()
    {
        $this->expectException(ExceptionRule::class);
        $fileElement = new File('foo');

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => \UPLOAD_ERR_NO_FILE
                ])

            ], parsedBody: [], method: 'post')
        );
        $uploadRule = new Upload(null, [
            'unknown'
        ]);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $testedMethod->invokeArgs($uploadRule, [$request->getFilesData('foo'), $fileElement]);
    }
}
