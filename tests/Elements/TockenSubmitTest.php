<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Form;
use PHPUnit\Framework\TestCase;

class TockenSubmitTest extends TestCase
{

    public function testTockenSubmitIfInclCounterDefault()
    {
        $form1 = new Form();
        $this->assertSame(md5('[]'), $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString());
        $form2 = new Form();
        $this->assertSame(md5('[]'), $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString());
    }

    public function testTockenSubmitIfInclCounterTrue()
    {
        $form1 = new Form(['inclCounter' => true]);
        $this->assertSame(md5('{"inclCounter":true}1'), $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString());
        $form2 = new Form(['inclCounter' => true]);
        $this->assertSame(md5('{"inclCounter":true}2'), $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString());
    }

}
