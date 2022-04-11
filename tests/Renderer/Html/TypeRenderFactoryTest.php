<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Color;
use Enjoys\Forms\Elements\Image;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Renderer\Html\TypeRenderFactory;
use Enjoys\Forms\Renderer\Html\TypesRender\Button;
use Enjoys\Forms\Renderer\Html\TypesRender\Input;

class TypeRenderFactoryTest extends TestCaseHtmlRenderer
{

    public function dataForTestCreate()
    {
        return [
            [
                fn() => new Text(uniqid()),
                Input::class
            ],
            [
                fn() => new Color(uniqid()),
                Input::class
            ],

            [
                fn() => new Image(uniqid()),
                Button::class
            ],
            [
                fn() => 'invalid',
                Input::class
            ],

            [
                fn() => new Radio(uniqid()),
                \Enjoys\Forms\Renderer\Html\TypesRender\Radio::class
            ],
        ];
    }

    /**
     * @dataProvider dataForTestCreate
     * @param $closure
     * @param $expect
     */
    public function testCreate($closure, $expect)
    {
        $element = $closure();
        if(!($element instanceof Element)){
            $this->expectError();
        }
        $this->assertInstanceOf($expect, HtmlRenderer::createTypeRender($element));
    }
}
