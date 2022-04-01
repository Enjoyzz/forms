<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Elements\Datalist;
use Enjoys\Forms\Renderer\ElementsRender\DatalistRender;


class DatalistRenderTest
{

    public function test_1()
    {
        $dl = new Datalist('foo');
        $dl->fill(['bar'], true);
        $o = new DatalistRender($dl);
        $this->assertEquals(
            '<input name="foo" list="foo"><datalist id="foo"><option id="bar" value="bar">bar</option></datalist>',
            $o->render()
        );
    }
}
