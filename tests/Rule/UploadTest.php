<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rule\Upload;
use Enjoys\Forms\Rules;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use Tests\Enjoys\Forms\_TestCase;
use Tests\Enjoys\Forms\Reflection;

class UploadTest extends _TestCase
{
    use Reflection;

    public function testValidateUploadManyRuled()
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
            Upload::REQUIRED,
            Upload::EXTENSIONS => [
                'pdf'
            ]
        ]);
        $uploadRule->setRequest($request);
        $this->assertEquals(true, $uploadRule->validate($fileElement));
    }

    public function testValidateUploadRuleFail()
    {
        $fileElement = new File('foo');
        $request = new ServerRequest(uploadedFiles: [
            'food' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => 0
            ])
        ], parsedBody: [], method: 'post');

        $uploadRule = new Upload([Upload::REQUIRED]);
        $uploadRule->setRequest($request);
        $this->assertEquals(false, $uploadRule->validate($fileElement));
    }

    public function testValidateUploadManyRuledFromForm()
    {
        $key = 'test';
        $this->session->set([
            'csrf_secret' => $key
        ]);
        $csrf = new Csrf($this->session);

        $request = new ServerRequest(uploadedFiles: [
            'foo' => UploadedFileCreator::createFromArray([
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'size' => 1000,
                'tmp_name' => 'test.pdf',
                'error' => 0
            ])
        ], parsedBody: [
            Form::_TOKEN_CSRF_ => $csrf->getCsrfToken($key)
        ], method: 'post');

        $form = new Form(request: $request);


        $el = $form->file('foo')->addRule(Rules::UPLOAD, [
            Upload::REQUIRED,
            Upload::EXTENSIONS => [
                'pdf'
            ]
        ]);

        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setValue($form, true);


        $this->assertEquals(true, $form->isSubmitted());
    }

    public function test_checkUnknown()
    {
        $this->expectExceptionMessage('Unknown Check Upload: [\Enjoys\Forms\Rule\UploadCheck\UnknownCheck]');
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
        $uploadRule = new Upload(['unknown']);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $testedMethod->invokeArgs($uploadRule, [$request->getUploadedFiles()['foo'], $fileElement]);
    }

    public function testValidateIfEmptyRulesParams()
    {
        $rule = new Upload([]);
        $method = $this->getPrivateMethod(Upload::class, 'check');
        $this->assertEquals(
            true,
            $method->invokeArgs($rule, [
                false,
                new File('foo')
            ])
        );
    }

    public function testValidateUploadRuledWithMultiple()
    {
        $fileElement = new File('foo');
        $fileElement->setMultiple();

        $request = new ServerRequest(uploadedFiles: [
            'foo' => [
                UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ]),
                UploadedFileCreator::createFromArray([
                    'name' => 'test2.pdf',
                    'type' => 'application/pdf',
                    'size' => 1002,
                    'tmp_name' => 'test2.pdf',
                    'error' => 0
                ])
            ]
        ], parsedBody: [], method: 'post');

        $uploadRule = new Upload([
            Upload::REQUIRED,
            Upload::EXTENSIONS => [
                'pdf'
            ]
        ]);
        $uploadRule->setRequest($request);
        $this->assertEquals(true, $uploadRule->validate($fileElement));
    }

    public function testValidateUploadRuleFailMultiple()
    {
        $fileElement = new File('foo');
        $fileElement->setMultiple();
        $request = new ServerRequest(uploadedFiles: [
            'food' => [
                UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ]),
                UploadedFileCreator::createFromArray([
                    'name' => 'test2.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test2.pdf',
                    'error' => 0
                ])
            ]
        ], parsedBody: [], method: 'post');

        $uploadRule = new Upload([Upload::REQUIRED]);
        $uploadRule->setRequest($request);
        $this->assertEquals(false, $uploadRule->validate($fileElement));
    }
}
