<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html;

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;

class HtmlRendererTest extends TestCaseHtmlRenderer
{
    public function testRendererHtml()
    {
        self::markTestSkipped();
        $form = new Form();
        $form->text('test');
        $renderer = new HtmlRenderer();
        $renderer->setForm($form);

        $this->assertSame('', $renderer->output());
    }
}
