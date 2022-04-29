<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\Elements\Button;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Renderer\Html\TypesRender\Input;
use Enjoys\Traits\Reflection;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class InputTest extends _TestCaseHtmlRenderer
{
    use Reflection;

    public function testGetElement()
    {
        $el = new Text('foo');
        $render = new Input($el);
        $this->assertSame($el, $render->getElement());
    }

    public function testDescriptionRender()
    {
        $el = new Text('foo');
        $el->setDescription('text');
        $type = new Input($el);
        $descriptionRender = $this->getPrivateMethod(Input::class, 'descriptionRender');
        $result = $descriptionRender->invoke($type);
        $this->assertSame('<small>text</small>', $result);
    }

    public function testDescriptionRenderWithNotDescriptionableElement()
    {
        $el = new Button('foo');
        $type = new Input($el);
        $descriptionRender = $this->getPrivateMethod(Input::class, 'descriptionRender');
        $result = $descriptionRender->invoke($type);
        $this->assertSame('', $result);
    }
}
