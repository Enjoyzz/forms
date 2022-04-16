<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Bootstrap4;

use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Bootstrap4\Bootstrap4RadioRender;

class Bootstrap4RadioRenderTest
{
    public function testAttributesGroupInRadioCheckbox()
    {
        $radio = new Radio('foo');
        $radio->fill([10], true);
        $radio->addClass('class1', Form::ATTRIBUTES_FILLABLE_BASE);
        $renderRadio = new Bootstrap4RadioRender($radio);
        $this->assertStringContainsString(
            '<div class="class1 custom-control custom-radio"><input type="radio" id="rb_10" value="10" class="custom-control-input" name="foo"><label class="custom-control-label" for="rb_10">10</label>',
            $renderRadio->render()
        );
    }
}
