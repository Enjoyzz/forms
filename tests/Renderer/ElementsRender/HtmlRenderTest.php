<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Html;
use Enjoys\Forms\Renderer\ElementsRender\HtmlRender;

class HtmlRenderTest
{

    public function testSimpleRender()
    {
        $element = new Html('<b></b>');
        $htmlRender = new HtmlRender($element);

        $this->assertEquals('<div><b></b></div>', $htmlRender->render());
    }

    public function testWithClass()
    {
        $element = new Html('<b></b>');
        $element->addClass('test1');
        $htmlRender = new HtmlRender($element);

        $this->assertEquals('<div class="test1"><b></b></div>', $htmlRender->render());
    }

    public function testWithAttributes()
    {
        $element = new Html('<b></b>');
        $element->setAttr(new Attribute('id', 1));
        $htmlRender = new HtmlRender($element);

        $this->assertEquals('<div id="1"><b></b></div>', $htmlRender->render());
    }
}
