<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class RadioTest extends _TestCaseHtmlRenderer
{
    public function testRadio()
    {

        $el = new Radio('test', 'Test Label', true);

        $el->fill([
            ['no', ['test', 'id' => 'new']],
            'yes'
        ]);

        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="test">Test Label</label>
<div><input type="radio" value="0" test id="new" name="test"><label for="new">no</label></div>
<div><input type="radio" id="test_1" value="1" name="test"><label for="test_1">yes</label></div>
HTML), $this->stringOneLine($render->render()));
    }

    public function testRadioCustomAddElements()
    {

        $el = new Radio('test', 'Test Label', true);
        $el->addElements([
         new Radio('yes', 'YES', false),
         (new Radio('no', 'NO'))
             ->addAttribute(AttributeFactory::create('test', ''))
             ->addAttribute(AttributeFactory::create('test'), Form::ATTRIBUTES_LABEL)
        ]);

        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="test">Test Label</label>
<div><input type="radio" id="test_yes" value="yes" name="test"><label for="test_yes">YES</label></div>
<div><input type="radio" value="no" id="no" test="" name="test"><label test for="no">NO</label></div>
HTML), $this->stringOneLine($render->render()));
    }

    public function testRadioWithError()
    {
        $el = (new Radio('foo'))->fill([1,2,3]);
        $el->setRuleError('error');
        $output = HtmlRenderer::createTypeRender($el);
        $this->assertStringContainsString('class="is-invalid"', $output->render());
    }
}
