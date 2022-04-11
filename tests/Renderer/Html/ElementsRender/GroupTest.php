<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\Elements\Button;
use Enjoys\Forms\Elements\Group;
use Enjoys\Forms\Elements\Reset;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Submit;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class GroupTest extends _TestCaseHtmlRenderer
{
    public function testBaseRender()
    {
        $el = new Group('FooBar');
        $el->setDescription('FooBarDesc');
        $el->add([
            new Text('foo', 'Foo'),
           ( new Select('bar', 'Bar'))->fill([1,2,3]),
        ]);

        $groupId = $el->getName();
        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<label for="$groupId">FooBar</label>
<div id='$groupId'>
<label for="foo">Foo</label>
<input type="text" id="foo" name="foo">
<label for="bar">Bar</label>
<select id="bar" name="bar">
<option id="0" value="0">1</option>
<option id="1" value="1">2</option>
<option id="2" value="2">3</option>
</select>
</div>
<small>FooBarDesc</small>
HTML
            ),
            $this->stringOneLine($output->render())
        );
    }

    public function testGroupButtons()
    {
        $el = new Group();
        $el->add([
            new Submit('foo', 'Foo'),
            new Reset('bar', 'Bar'),
            new Button('baz', 'Baz'),
        ]);

        $groupId = $el->getName();
        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<div id='$groupId'>
<input type="submit" id="foo" name="foo" value="Foo">
<input type="reset" id="bar" name="bar" value="Bar">
<button id="baz" name="baz">Baz</button>
</div>
HTML
            ),
            $this->stringOneLine($output->render())
        );
    }
}
