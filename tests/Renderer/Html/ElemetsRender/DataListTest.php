<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElemetsRender;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Elements\Datalist;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class DataListTest extends _TestCaseHtmlRenderer
{
    public function testDataList()
    {
        $el = new Datalist('test', 'Label');
        $el->fill([1, 'notvalue' => 'value', 3]);
        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<label for="test">Label</label>
<input id="test" name="test" list="test-list">
<datalist id='test-list'>
<option value="1">
<option value="value">
<option value="3">
</datalist>
HTML
            ),
            $this->stringOneLine($render->render())
        );
    }

    public function testDataListChangeId()
    {
        $el = new Datalist('test', 'Label');
        $el->setAttr(AttributeFactory::create('id', 'new-id'));
        $el->fill([1, 2, 3]);

        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<label for="new-id">Label</label>
<input name="test" list="test-list" id="new-id">
<datalist id='test-list'>
<option value="1">
<option value="2">
<option value="3">
</datalist>
HTML
            ),
            $this->stringOneLine($render->render())
        );
    }
}
