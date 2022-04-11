<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html;

use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;

class HtmlRendererTest extends TestCaseHtmlRenderer
{
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
        <option id="0" value="0">1</option><option id="1" value="1">2</option><option id="2" value="2">3</option>
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
