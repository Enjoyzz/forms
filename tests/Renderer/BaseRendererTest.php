<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\BaseRenderer;
use Tests\Enjoys\Forms\TestCase;


class BaseRendererTest extends TestCase
{
    private BaseRenderer $render;

    protected function setUp(): void
    {
        $form = new Form('get');
        $form->text('foo');
        $form->color('bar')->setAttr(AttributeFactory::create('disabled'));


        $this->render = new BaseRenderer();
        $this->render->setForm($form);


    }

    public function testRenderMethod()
    {
        $result = $this->render->render();
        $this->assertStringContainsString('<form method="GET">', $result);
    }
    public function testRenderToken()
    {
        $result = $this->render->render();
        $this->assertStringContainsString('<input type="hidden" name="_token_submit" value="', $result);
    }
    public function testRenderElementText()
    {
        $result = $this->render->render();
        $this->assertStringContainsString('<input type="text" id="foo" name="foo">', $result);
    }
    public function testRenderElementColor()
    {
        $result = $this->render->render();
        $this->assertStringContainsString('<input type="color" id="bar" name="bar" disabled>', $result);
    }
}
