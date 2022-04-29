<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer;

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\AbstractRenderer;
use Tests\Enjoys\Forms\Renderer\Html\_TestCaseHtmlRenderer;

class AbstractRendererTest extends _TestCaseHtmlRenderer
{
    public function testGetForm()
    {
        $form = new Form('get');
        $mock = $this->getMockForAbstractClass(AbstractRenderer::class, ['form' => $form]);
        $this->assertSame($form, $mock->getForm());
    }
}
