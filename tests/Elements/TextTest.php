<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Form;
use Tests\Enjoys\Forms\_TestCase;

class TextTest extends _TestCase
{

    public function testSetDefaultsWithHtmlEntities()
    {
        $form = new Form();
        $form->setDefaults([
            'test' => '<b class="test">'
        ]);
        $el = $form->text('test');
        $this->assertSame('&lt;b class=&quot;test&quot;&gt;', $el->getAttribute('value')->getValueString());
    }

}
