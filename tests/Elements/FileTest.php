<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\File;
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
        $elements = $form->getElement('MAX_FILE_SIZE');
        $this->assertSame('25', $elements->getAttribute('value')->getValueString());
    }

    public function test_max_file_size2()
    {
        $form = new Form();
        $form->file('file');
        $elements = $form->getElement('MAX_FILE_SIZE');
        $this->assertSame(
            (string)\iniSize2bytes(ini_get('upload_max_filesize')),
            $elements->getAttribute('value')->getValueString()
        );
    }


    public function test_enctype_method()
    {
        $form = new Form();
        $form->file('file');
        $this->assertSame('POST', $form->getAttribute('method')->getValueString());
        $this->assertSame('multipart/form-data', $form->getAttribute('enctype')->getValueString());
    }

    public function test_invalid_add_rule()
    {
        $this->expectException(ExceptionRule::class);
        $form = new Form();
        $form->file('1')->addRule(Rules::REQUIRED);
    }

    public function testSetAccept()
    {
        $el = new File('name');
        $el->addAccept('image/*');
        $this->assertSame('<input type="file" id="name" name="name" accept="image/*">', $el->baseHtml());
        $el->addAccept('video/*');
        $this->assertSame('<input type="file" id="name" name="name" accept="image/*,video/*">', $el->baseHtml());
    }

    public function testSetAccepts()
    {
        $el = new File('name');
        $el->addAccept('image/*');
        $this->assertSame('<input type="file" id="name" name="name" accept="image/*">', $el->baseHtml());
        $el->setAccepts(['video/*', 'image/jpeg']);
        $this->assertSame('<input type="file" id="name" name="name" accept="video/*,image/jpeg">', $el->baseHtml());
    }

    public function testSetMultiple()
    {
        $el = new File('name');
        $el->setMultiple();
        $this->assertSame('<input type="file" id="name" name="name" multiple>', $el->baseHtml());
    }
}
