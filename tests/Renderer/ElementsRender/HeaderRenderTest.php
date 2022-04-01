<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;


use Enjoys\Forms\Elements\Header;
use Enjoys\Forms\Renderer\ElementsRender\HeaderRender;

class HeaderRenderTest
{
    public function test_1()
    {
        $o = new HeaderRender(new Header('foo'));
        $this->assertEquals('<div>foo</div>', $o->render());
    }
}
