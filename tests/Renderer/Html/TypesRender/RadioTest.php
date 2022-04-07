<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\TypesRender;


use Enjoys\Forms\Elements\Radio;
use Tests\Enjoys\Forms\Renderer\Html\TestCaseHtmlRenderer;

class RadioTest extends TestCaseHtmlRenderer
{
    public function testRadio()
    {
        self::markTestSkipped();
        $el = new Radio('test', 'Test Label');
//        $el->addElements([
//            new Radio('Yes'),
//            (new Radio('No'))
//                ->addAttr(AttributeFactory::create('test', ''))
//                ->addAttr(AttributeFactory::create('test'), Form::ATTRIBUTES_LABEL)
//        ]);
        $el->fill([
            ['no', ['test', 'id' => 'new']],
            'yes'
        ]);

        $renderer = new \Enjoys\Forms\Renderer\Html\TypesRender\Radio($el);
        var_dump($renderer->render());
    }
}
