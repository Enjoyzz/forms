<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class SelectTest extends _TestCaseHtmlRenderer
{
    public function testOptGroupRenderer()
    {
        $el = new Select('foo', 'bar');
        $el->setOptgroup(
            'group1',
            [1, 2, 3],
            ['class' => 'text-danger']
        )->setOptgroup(
            'group2',
            [4, 5, 6]
        );

        $output = HtmlRenderer::createTypeRender($el);
        $this->assertSame(
            $this->stringOneLine(
                <<<HTML
<label for="foo">bar</label>
<select id="foo" name="foo">
<optgroup label="group1" class="text-danger">
    <option value="0">1</option>
    <option value="1">2</option>
    <option value="2">3</option>
</optgroup>
<optgroup label="group2">
    <option value="0">4</option>
    <option value="1">5</option>
    <option value="2">6</option>
</optgroup>
</select>
HTML
            ),
            $this->stringOneLine($output->render())
        );
    }
}
