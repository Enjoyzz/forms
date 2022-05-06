<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Rule\Upload;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Webmozart\Assert\InvalidArgumentException;

class UploadTest extends TestCase
{
    use Reflection;

    public function testValidateUploadRule()
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

    public function testValidateUploadRuleFail()
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

        $this->assertSame("Размер файла (1000 B) превышает допустимый размер: 999 B", $fileElement->getRuleErrorMessage());
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
            'maxsize' => 1000
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

    public function testCheckMaxsizeInvalidThresholdParam()
    {
        $this->expectError();
        $uploadRule = new Upload();
        $testedMethod = $this->getPrivateMethod(Upload::class, 'checkMaxsize');
        $testedMethod->invokeArgs($uploadRule, [
            'value' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => 0
            ]),
            'ruleOpts' => 'non numeric',
            'element' => new File('foo')
        ]);
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

    public function testValidateIfEmptyRulesParams()
    {
        $rule = new Upload();
        $method = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            true,
            $method->invokeArgs($rule, [
                [],
                new File('foo')
            ])
        );
    }

    /**
     * @dataProvider dataForTestParseRuleOpts
     */
    public function testParseRuleOpts($input, $expect)
    {
        if ($expect === false) {
            $this->expectException(InvalidArgumentException::class);
        }
        $rule = new Upload();
        $method = $this->getPrivateMethod(Upload::class, 'parseRuleOpts');
        $result = $method->invokeArgs($rule, [$input]);
        $this->assertSame($expect, $result);
    }

    public function dataForTestParseRuleOpts()
    {
        return [
               [[1, 2], false],
               [[1, '2'], ['param' => 1, 'message' => '2']],
               [[[1], null], ['param' => [1], 'message' => null]],
               ['xl', ['param' => 'xl', 'message' => null]],
        ];
    }
}
