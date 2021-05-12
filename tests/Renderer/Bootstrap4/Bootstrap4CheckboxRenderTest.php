<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Bootstrap4;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Bootstrap4\Bootstrap4CheckboxRender;
use PHPUnit\Framework\TestCase;

class Bootstrap4CheckboxRenderTest extends TestCase
{
    public function testAttributesGroup()
    {
        $checkbox = new Checkbox('foo');
        $checkbox->fill([1]);
        $checkbox->addClass('class1 class2', Form::ATTRIBUTES_FILLABLE_BASE);
        $renderCheckbox = new Bootstrap4CheckboxRender($checkbox);
        $this->assertStringContainsString(
            '<div class="class1 class2 custom-control custom-checkbox"><input type="checkbox" id="cb_0" value="0" class="custom-control-input" name="foo[]"><label class="custom-control-label" for="cb_0">1</label>',
            $renderCheckbox->render()
        );
    }
}
