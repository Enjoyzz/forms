<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Form;
use Tests\Enjoys\Forms\_TestCase;

class TokenSubmitTest extends _TestCase
{
    public function testTokenSubmitIfOptionsIdAfterInitDifferent()
    {
        $form1 = new Form();
        $f1_hash = md5(json_encode($form1->getOptions()));
        $this->assertSame($f1_hash, $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());
        $form2 = new Form();
        $form2->setId('test');
        $f2_hash = md5(json_encode($form2->getOptions()));
        $this->assertSame($f2_hash, $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());

        $this->assertNotSame($f1_hash, $f2_hash);
    }

    public function testTokenSubmitIfOptionsActionAfterInitDifferent()
    {
        $form1 = new Form();
        $f1_hash = md5(json_encode($form1->getOptions()));
        $this->assertSame($f1_hash, $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());
        $form2 = new Form();
        $form2->setAction('/test');
        $f2_hash = md5(json_encode($form2->getOptions()));
        $this->assertSame($f2_hash, $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());

        $this->assertNotSame($f1_hash, $f2_hash);
    }

    public function testTokenSubmitIfOptionsMethodAfterInitDifferent()
    {
        $form1 = new Form();
        $f1_hash = md5(json_encode($form1->getOptions()));
        $this->assertSame($f1_hash, $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());
        $form2 = new Form();
        $form2->setMethod('get');
        $f2_hash = md5(json_encode($form2->getOptions()));
        $this->assertSame($f2_hash, $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());

        $this->assertNotSame($f1_hash, $f2_hash);
    }

    public function testTokenSubmitIfOptionsSame()
    {
        $form1 = new Form();
        $f1_hash = md5(json_encode($form1->getOptions()));
        $this->assertSame($f1_hash, $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());
        $form2 = new Form();
        $f2_hash = md5(json_encode($form2->getOptions()));
        $this->assertSame($f2_hash, $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());
        $this->assertSame($f1_hash, $f2_hash);
    }
}
