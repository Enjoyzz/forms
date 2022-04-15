<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Color;
use Enjoys\Forms\Elements\Image;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypesRender\Button;
use Enjoys\Forms\Renderer\Html\TypesRender\Input;

class HtmlRendererTest extends _TestCaseHtmlRenderer
{
    public function dataForTestCreateTypeRender()
    {
        return [
            [
                fn() => new Text(uniqid()),
                Input::class
            ],
            [
                fn() => new Color(uniqid()),
                Input::class
            ],

            [
                fn() => new Image(uniqid()),
                Button::class
            ],
            [
                fn() => 'invalid',
                Input::class
            ],

            [
                fn() => new Radio(uniqid()),
                \Enjoys\Forms\Renderer\Html\TypesRender\Radio::class
            ],
        ];
    }

    /**
     * @dataProvider dataForTestCreateTypeRender
     * @param $closure
     * @param $expect
     */
    public function testCreateTypeRender($closure, $expect)
    {
        $element = $closure();
        if (!($element instanceof Element)) {
            $this->expectError();
        }
        $this->assertInstanceOf($expect, HtmlRenderer::createTypeRender($element));
    }


    public function testRendererHtml()
    {
        $form = new Form();
        $form->text('test', 'Text Label Input');
        $form->group('Group Label', 'group_id')->add([
            new Text('foo', 'Foo'),
            (new Select('bar', 'Bar'))->fill([1, 2, 3]),
        ]);

        $form->submit('sbmt1', 'Submit button');

        $renderer = new HtmlRenderer();
        $renderer->setForm($form);

        $_token_csrf = $form->getElement(Form::_TOKEN_CSRF_)->getAttr('value')->getValueString();
        $_token_submit = $form->getElement(Form::_TOKEN_SUBMIT_)->getAttr('value')->getValueString();

        $this->assertEquals($form, $renderer->getForm());

        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<form method="POST">
<input type="hidden" name="_token_csrf" value="$_token_csrf">
<input type="hidden" name="_token_submit" value="$_token_submit">

<div>
<label for="test">Text Label Input</label>
<input type="text" id="test" name="test">
</div>

<div>
<label for="group_id">Group Label</label>
    <div id='group_id'>
        <label for="foo">Foo</label>
        <input type="text" id="foo" name="foo">
        <label for="bar">Bar</label>
        <select id="bar" name="bar">
        <option value="0">1</option><option value="1">2</option><option value="2">3</option>
        </select>
    </div>
</div>

<div>
<input type="submit" id="sbmt1" name="sbmt1" value="Submit button">
</div>
</form>
HTML
            ),
            $this->stringOneLine($renderer->output())
        );
    }
}
