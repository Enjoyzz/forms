<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Renderer\ElementsRender\BaseElement;
use Enjoys\Forms\Rules;
use Tests\Enjoys\Forms\Reflection;


class BaseElementTest
{
    
    use Reflection;
    
    public function test_renderDescription()
    {
        $element = new Text('foo');
        $base = new BaseElement($element);
        $method = $this->getPrivateMethod(BaseElement::class, 'renderDescription');
        $this->assertEquals(null, $method->invokeArgs($base, [$element, 'div']));
        $element->setDescription('bar');
        $this->assertEquals('<div>bar</div>', $method->invokeArgs($base, [$element, 'div']));
               
    }
    public function test_renderValidation()
    {
        $element = new Text('foo');
        $base = new BaseElement($element);
        $method = $this->getPrivateMethod(BaseElement::class, 'renderValidation');
        $this->assertEquals(null, $method->invokeArgs($base, [$element, 'small']));
        $element->setRuleError('bar');
        $this->assertEquals('<small>bar</small>', $method->invokeArgs($base, [$element, 'small']));
               
    }
    public function test_renderLabel()
    {
        $element = new Text('foo', 'bar');
        $base = new BaseElement($element);
        $method = $this->getPrivateMethod(BaseElement::class, 'renderLabel');
        $this->assertEquals('<label for="foo">bar</label>', $method->invokeArgs($base, [$element]));
        $element->addRule(Rules::REQUIRED);
        $this->assertEquals('<label for="foo">bar&nbsp;<sup>strong</sup></label>', $method->invokeArgs($base, [$element, '&nbsp;<sup>strong</sup>']));
               
    }
}
