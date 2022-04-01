<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;


use Enjoys\Forms\Elements\Button;
use Enjoys\Forms\Renderer\ElementsRender\ButtonRender;

class ButtonRenderTest
{
 public function test_1()
    {
        $o = new ButtonRender(new Button('foo', 'bar'));
        $this->assertEquals('<button id="foo" name="foo">bar</button>', $o->render());
    }
}
