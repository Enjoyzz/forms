<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Renderer\ElementsRender\SelectRender;

class SelectRenderTest
{
    public function test_1()
    {
        $s = new Select('foo');
        $s->fill(['bar'], true);
        $o = new SelectRender($s);
        $this->assertStringContainsString('<select id="foo" name="foo"><option id="bar" value="bar">bar</option></select>', $o->render());
    }
    public function test_optgroup()
    {
        $s = new Select('foo');
        $s->setOptgroup('bar', [
            'baz'
        ], [], true);
        $o = new SelectRender($s);
        $this->assertStringContainsString('<select id="foo" name="foo"><optgroup label="bar"><option id="baz" value="baz">baz</option></optgroup></select>', $o->render());
    }
}
