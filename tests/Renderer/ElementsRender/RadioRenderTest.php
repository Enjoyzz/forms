<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\ElementsRender\CheckboxRender;
use Enjoys\Forms\Renderer\ElementsRender\RadioRender;
use PHPUnit\Framework\TestCase;

/**
 * Class RadioRenderTest
 * @package Tests\Enjoys\Forms\Renderer\ElementsRender
 */
class RadioRenderTest extends TestCase
{
    public function test_1()
    {
        $r = new Radio('foo');
        $r->fill(['bar'], true);
        $r->setRuleError('error');
        $o = new RadioRender($r);
        $this->assertStringContainsString(
            '<div><input type="radio" id="rb_bar" value="bar" class="is-invalid" name="foo"><label for="rb_bar">bar</label>',
            $o->render()
        );
    }

    public function testAttributesGroupInRadioCheckbox()
    {
        $checkbox = new Checkbox('foo');
        $checkbox->fill([1]);
        $checkbox->addClass('class1 class2', Form::ATTRIBUTES_FILLABLE_BASE);
        $renderCheckbox = new CheckboxRender($checkbox);
        $this->assertStringContainsString(
            '<div class="class1 class2"><input type="checkbox" id="cb_0" value="0" name="foo[]"><label for="cb_0">1</label>',
            $renderCheckbox->render()
        );

        $radio = new Radio('foo');
        $radio->fill([10], true);
        $radio->addClass('class1 class2', Form::ATTRIBUTES_FILLABLE_BASE);
        $renderRadio = new RadioRender($radio);
        $this->assertStringContainsString(
            '<div class="class1 class2"><input type="radio" id="rb_10" value="10" name="foo"><label for="rb_10">10</label>',
            $renderRadio->render()
        );
    }
}
