<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\ElementsRender;

use Enjoys\Forms\Elements\Html;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{

    public function testHtmlBase()
    {
        $el = new Html('<b>test</b>');
        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame('<div><b>test</b></div>', $render->render());
    }

    public function testHtmlBaseWithAttributes()
    {
        $el = new Html('<b>test</b>');
        $el->addClasses(['test', 'test2']);
        $render = HtmlRenderer::createTypeRender($el);
        $this->assertSame('<div class="test test2"><b>test</b></div>', $render->render());
    }

}
