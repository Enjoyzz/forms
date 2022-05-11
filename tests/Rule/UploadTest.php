<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rule\Upload;
use Enjoys\Forms\Rules;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use HttpSoft\ServerRequest\UploadedFileCreator;
use Tests\Enjoys\Forms\_TestCase;

class UploadTest extends _TestCase
{
    use Reflection;

    public function testValidateUploadManyRuled()
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

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [
                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => 0
                ])
            ], parsedBody: [
                Form::_TOKEN_CSRF_ => $csrf->getCsrfToken($key)
            ], method: 'post')
        );

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

        $request = new ServerRequestWrapper(
            new ServerRequest(uploadedFiles: [

                'foo' => UploadedFileCreator::createFromArray([
                    'name' => 'test.pdf',
                    'type' => 'application/pdf',
                    'size' => 1000,
                    'tmp_name' => 'test.pdf',
                    'error' => \UPLOAD_ERR_NO_FILE
                ])

            ])
        );
        $uploadRule = new Upload(['unknown']);
        $testedMethod = $this->getPrivateMethod(Upload::class, 'check');
        $testedMethod->invokeArgs($uploadRule, [$request->getFilesData('foo'), $fileElement]);
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

//    /**
//     * @dataProvider dataForTestParseRuleOpts
//     */
//    public function testParseRuleOpts($input, $expect)
//    {
//        self::markTestSkipped();
//        if ($expect === false) {
//            $this->expectException(InvalidArgumentException::class);
//        }
//        $rule = new Upload();
//        $method = $this->getPrivateMethod(Upload::class, 'parseRuleOpts');
//        $result = $method->invokeArgs($rule, [$input]);
//        $this->assertSame($expect, $result);
//    }
//
//    public function dataForTestParseRuleOpts()
//    {
//        return [
//               [[1, 2], false],
//               [[1, '2'], ['param' => 1, 'message' => '2']],
//               [[[1], null], ['param' => [1], 'message' => null]],
//               ['xl', ['param' => 'xl', 'message' => null]],
//        ];
//    }
}
