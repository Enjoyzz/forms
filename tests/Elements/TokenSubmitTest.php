<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Form;
use Tests\Enjoys\Forms\_TestCase;

class TokenSubmitTest extends _TestCase
{
    public function testTokenSubmitIfOptionsDifferent()
    {
        $form1 = new Form();
        $f1_hash = md5(json_encode($form1->getOptions()));
        $this->assertSame($f1_hash, $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValueString());
        $form2 = new Form('get');
        $form2->setAction('/kkkk');
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

        $this->assertNotSame($f1_hash, $f2_hash);
    }
}
