<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Renderer\ElementsRender;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Renderer\ElementsRender\BaseElement;
use Enjoys\Forms\Rules;
use PHPUnit\Framework\TestCase;
use Tests\Enjoys\Forms\Reflection;

/**
 * Description of BaseElementTest
 *
 * @author Enjoys
 */
class BaseElementTest extends TestCase
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
