<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Tests\Enjoys\Forms\Renderer\Html\TestCaseHtmlRenderer;

class CheckboxTest extends TestCaseHtmlRenderer
{
    public function testCheckbox()
    {
        $el = new \Enjoys\Forms\Elements\Checkbox('test', 'Test Label');

        $el->fill([
            ['no', ['test', 'id' => 'new']],
            'yes'
        ]);

        $render = TypeRenderFactory::create($el);
        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<label for="cb_test">Test Label</label>
<div><input type="checkbox" value="0" test id="new" name="test[]"><label for="new">no</label></div>
<div><input type="checkbox" id="cb_1" value="1" name="test[]"><label for="cb_1">yes</label></div>
HTML
            ),
            $this->stringOneLine($render->render())
        );
    }

    public function testCheckboxWidthCustomAttributes()
    {
        //   self::markTestSkipped();
        $el = new \Enjoys\Forms\Elements\Checkbox('test', 'Test Label');
        $el->addElements([
            (new \Enjoys\Forms\Elements\Checkbox('Yes', 'Yes Label'))->setAttr(AttributeFactory::create('id', 'new-id')),
            (new \Enjoys\Forms\Elements\Checkbox('No'))
                ->addAttr(AttributeFactory::create('test', ''))
                ->addAttr(AttributeFactory::create('test'), Form::ATTRIBUTES_LABEL)
        ]);

        $render = TypeRenderFactory::create($el);

        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<label for="cb_test">Test Label</label>
<div><input type="checkbox" value="Yes" id="new-id" name="test[]"><label for="new-id">Yes Label</label></div>
<div><input type="checkbox" id="cb_No" value="No" test="" name="test[]"><label test for="cb_No"></label></div>
HTML
            ),
            $this->stringOneLine($render->render())
        );
    }

}
