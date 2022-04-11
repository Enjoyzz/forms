<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Renderer\Html\TypesRender\Input;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class InputTest extends _TestCaseHtmlRenderer
{

    public function testGetElement()
    {
        $el = new Text('foo');
        $render = new Input($el);
        $this->assertSame($el, $render->getElement());
    }
}
