<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Form;
use Tests\Enjoys\Forms\_TestCase;

class TokenSubmitTest extends _TestCase
{

    public function testTockenSubmitIfInclCounterDefault()
    {
        $form1 = new Form();
        $this->assertSame(md5(json_encode($form1->getOptions())), $form1->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString());
        $form2 = new Form();
        $this->assertSame(md5(json_encode($form2->getOptions())), $form2->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString());
    }
}
