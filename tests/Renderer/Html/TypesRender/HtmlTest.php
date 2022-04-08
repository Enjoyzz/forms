<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{

    public function testHtmlBase()
    {
        $el = new \Enjoys\Forms\Elements\Html('<b>test</b>');
        $render = TypeRenderFactory::create($el);
        $this->assertSame('<div><b>test</b></div>', $render->render());
    }

    public function testHtmlBaseWithAttributes()
    {
        $el = new \Enjoys\Forms\Elements\Html('<b>test</b>');
        $el->addClasses(['test', 'test2']);
        $render = TypeRenderFactory::create($el);
        $this->assertSame('<div class="test test2"><b>test</b></div>', $render->render());
    }

}
