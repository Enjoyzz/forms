<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\Renderer\Html\TypesRender\Checkbox;
use PHPUnit\Framework\TestCase;

class CheckboxTest extends TestCase
{
    public function testCheckbox()
    {
        self::markTestSkipped();
        $el = new \Enjoys\Forms\Elements\Checkbox('test', 'Test Label');
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

        $renderer = new Checkbox($el);
        var_dump($renderer->render());
    }

}
