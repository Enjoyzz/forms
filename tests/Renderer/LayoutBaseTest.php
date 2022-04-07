<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer;


use Enjoys\Forms\Renderer\LayoutBase;

class LayoutBaseTest
{

//
//    public function test_rendereLabel()
//    {
//        $element = new Text(
//              'foo', 'bar'
//        );
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderLabel');
//        $this->assertEquals('<label for="foo">bar</label>', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_rendereLabel2()
//    {
//        $this->markTestIncomplete();
//        $element = new Text(
//               'foo', 'bar'
//        );
//        $element->addClass('test', Form::ATTRIBUTES_LABEL);
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderLabel');
//        $this->assertEquals('<label for="foo" class="test">bar</label>', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_rendereLabel_empty()
//    {
//        $element = new Text(
//            'foo'
//        );
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderLabel');
//        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_renderDescription()
//    {
//        $element = new Text(
//               'foo'
//        );
//        $element->setDescription('desc');
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderDescription');
//        $this->assertEquals('<small>desc</small>', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_renderDescription2()
//    {
//        $element = new Text(
//             'foo'
//        );
//        $element->setDescription('desc');
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderDescription');
//        $this->assertEquals('<div>desc</div>', $method->invokeArgs($layout, [$element, 'div']));
//    }
//
//    public function test_renderDescription3()
//    {
//        $element = new Text(
//              'foo'
//        );
//        $element->setDescription('desc');
//        $element->addClass('test', Form::ATTRIBUTES_DESC);
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderDescription');
//        $this->assertEquals('<small class="test">desc</small>', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_renderDescription_empty()
//    {
//        $element = new Text(
//            'foo'
//        );
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderDescription');
//        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_renderValidation()
//    {
//        $element = new Text(
//              'foo'
//        );
//        $element->setRuleError('error');
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderValidation');
//        $this->assertEquals('<div>error</div>', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_renderValidation_empty()
//    {
//        $element = new Text(
//             'foo'
//        );
//        $layout = new LayoutBase($element);
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderValidation');
//        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
//    }
//
//    public function test_renderBody()
//    {
//$this->markTestIncomplete();
//        $method = $this->getPrivateMethod(LayoutBase::class, 'renderBody');
//
//        $element = new Text(
//               'foo'
//        );
//        $this->assertEquals('<input type="text" id="foo" name="foo">', $method->invokeArgs(
//                        new LayoutBase($element),
//                        [$element])
//        );
//
//        $element = new Textarea(
//                'foo'
//        );
//        $this->assertEquals('<textarea id="foo" name="foo"></textarea>', $method->invokeArgs(
//                        new LayoutBase($element),
//                        [$element]
//        ));
//
//        $element = new Button(
//            'foo'
//        );
//        $this->assertEquals('<button id="foo" name="foo"></button>', $method->invokeArgs(
//                        new LayoutBase($element),
//                        [$element]
//        ));
//
//        $element = new Header(
//            'foo'
//        );
//        $this->assertEquals('foo', $method->invokeArgs(
//                        new LayoutBase($element),
//                        [$element]
//        ));
//
//        $element = new Option(
//              'foo', 'bar'
//        );
//        $this->assertEquals('<option id="foo" value="foo">bar</option>', $method->invokeArgs(
//                        new LayoutBase($element),
//                        [$element]
//        ));
//
//        $element = new Captcha(
//
//        );
//        $this->assertStringContainsString('<img src="data:image/jpeg;base64', $method->invokeArgs(
//                        new LayoutBase($element),
//                        [$element]
//        ));
//    }
//
//    public function test_render()
//    {
//        $element = new Text(
//             'foo'
//        );
//        $element->setLabel('bar')->setDescription('desc')->setRuleError('error');
//        $layout = new LayoutBase($element);
//
//        $this->assertEquals('<label for="foo">bar</label><input type="text" id="foo" name="foo"><small>desc</small><div>error</div>', $layout->render());
//    }
}
