<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer;

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\BaseRenderer;


class BaseRendererTest
{
    public function test_render()
    {
        $form = new Form(['method'=>'get']);
        $form->text('foo');
        $form->color('bar');
        $render = new BaseRenderer();
        $render->setForm($form);
        
        $result = $render->render();
        $this->assertStringContainsString('<form method="GET">', $result);
        $this->assertStringContainsString('<input type="hidden" name="_token_submit" value="', $result);
        $this->assertStringContainsString('<input type="text" id="foo" name="foo">', $result);
        $this->assertStringContainsString('<input type="color" id="bar" name="bar">', $result);
    }
}
