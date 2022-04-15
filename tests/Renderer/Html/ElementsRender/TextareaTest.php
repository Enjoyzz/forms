<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\Elements\Textarea;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Enjoys\Traits\Reflection;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class TextareaTest extends _TestCaseHtmlRenderer
{
    use Reflection;

    public function testBaseRender()
    {
        $el = new Textarea('foo', 'bar');
        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="foo">bar</label>
<textarea id="foo" name="foo"></textarea>
HTML), $this->stringOneLine($output->render()));
    }

    public function testRenderWithError()
    {
        $el = new Textarea('foo', 'bar');
        $el->setRuleError('this field is required');

        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="foo">bar</label>
<textarea id="foo" name="foo"></textarea>
<div>this field is required</div>
HTML), $this->stringOneLine($output->render()));
    }

    public function testRenderWithErrorIsRequired()
    {
        $el = new Textarea('foo', 'bar');
        $el->setRuleError('this field is required');
        $required = $this->getPrivateProperty($el, 'required');
        $required->setValue($el, true);

        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="foo">bar&nbsp;<sup>*</sup></label>
<textarea id="foo" name="foo"></textarea>
<div>this field is required</div>
HTML), $this->stringOneLine($output->render()));
    }

    public function testRenderWithErrorIsRequiredAndLabelEmpty()
    {
        $el = new Textarea('foo');
        $el->setRuleError('this field is required');
        $required = $this->getPrivateProperty($el, 'required');
        $required->setValue($el, true);

        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<textarea id="foo" name="foo"></textarea>
<div>this field is required</div>
HTML), $this->stringOneLine($output->render()));
    }

    public function testRenderWithErrorAndDescrtiption()
    {
        $el = new Textarea('foo', 'bar');
        $el->setDescription('this is description');
        $el->setRuleError('this field is required');

        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="foo">bar</label>
<textarea id="foo" name="foo"></textarea>
<small>this is description</small>
<div>this field is required</div>
HTML), $this->stringOneLine($output->render()));
    }
}
