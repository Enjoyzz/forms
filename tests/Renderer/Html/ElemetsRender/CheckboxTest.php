<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElemetsRender;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class CheckboxTest extends _TestCaseHtmlRenderer
{
    public function testCheckbox()
    {
        $el = new Checkbox('test', 'Test Label');

        $el->fill([
            ['no', ['test', 'id' => 'new']],
            'yes'
        ]);

        $render = HtmlRenderer::createTypeRender($el);
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
        $el = new Checkbox('test', 'Test Label');
        $el->addElements([
            (new Checkbox('Yes', 'Yes Label'))->setAttr(AttributeFactory::create('id', 'new-id')),
            (new Checkbox('No'))
                ->addAttr(AttributeFactory::create('test', ''))
                ->addAttr(AttributeFactory::create('test'), Form::ATTRIBUTES_LABEL)
        ]);

        $render = HtmlRenderer::createTypeRender($el);

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
