<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function test_max_file_size()
    {

        $form = new Form();
        $form->file('file')->setMaxFileSize(25);
        $elements = $form->getElements()['MAX_FILE_SIZE'];
        $this->assertSame('25', $elements->getAttr('value')->getValueString());
    }

    public function test_max_file_size2()
    {

        $form = new Form();
        $form->file('file');
        $elements = $form->getElements()['MAX_FILE_SIZE'];
        $this->assertSame((string) \iniSize2bytes(ini_get('upload_max_filesize')), $elements->getAttr('value')->getValueString());
    }



    public function test_enctype_method()
    {
        $form = new Form();
        $form->file('file');
        $this->assertSame('POST', $form->getAttr('method')->getValueString());
        $this->assertSame('multipart/form-data', $form->getAttr('enctype')->getValueString());
    }

    public function test_invalid_add_rule()
    {
        $this->expectException(ExceptionRule::class);
        $form = new Form();
        $form->file('1')->addRule(Rules::REQUIRED);
    }
}
