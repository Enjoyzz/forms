<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\TypesRender;


use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Tests\Enjoys\Forms\Renderer\Html\TestCaseHtmlRenderer;

class RadioTest extends TestCaseHtmlRenderer
{
    public function testRadio()
    {

        $el = new Radio('test', 'Test Label');
//        $el->addElements([
//            new Radio('Yes'),
//            (new Radio('No'))
//                ->addAttr(AttributeFactory::create('test', ''))
//                ->addAttr(AttributeFactory::create('test'), Form::ATTRIBUTES_LABEL)
//        ]);
        $el->fill([
            ['no', ['test', 'id' => 'new']],
            'yes'
        ]);

        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="rb_test">Test Label</label>
<div><input type="radio" value="0" test id="new" name="test"><label for="new">no</label></div>
<div><input type="radio" id="rb_1" value="1" name="test"><label for="rb_1">yes</label></div>
HTML), $this->stringOneLine($render->render()));
    }

       public function testRadioCustomAddElements()
    {

        $el = new Radio('test', 'Test Label');
        $el->addElements([
            new Radio('yes', 'YES'),
            (new Radio('no', 'NO'))
                ->addAttr(AttributeFactory::create('test', ''))
                ->addAttr(AttributeFactory::create('test'), Form::ATTRIBUTES_LABEL)
        ]);

        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame($this->stringOneLine(<<<HTML
<label for="rb_test">Test Label</label>
<div><input type="radio" id="rb_yes" value="yes" name="test"><label for="rb_yes">YES</label></div>
<div><input type="radio" id="rb_no" value="no" test="" name="test"><label test for="rb_no">NO</label></div>
HTML), $this->stringOneLine($render->render()));
    }



}
