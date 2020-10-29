<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
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

namespace Tests\Enjoys\Forms\Renderer;

/**
 * Description of LayoutBaseTest
 *
 * @author deadl
 */
class LayoutBaseTest 
{
    use \Tests\Enjoys\Forms\Reflection;

    public function test_rendereLabel()
    {
        $element = new \Enjoys\Forms\Elements\Text(
              'foo', 'bar'
        );
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderLabel');
        $this->assertEquals('<label for="foo">bar</label>', $method->invokeArgs($layout, [$element]));
    }

    public function test_rendereLabel2()
    {
        $this->markTestIncomplete();
        $element = new \Enjoys\Forms\Elements\Text(
               'foo', 'bar'
        );
        $element->addClass('test', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderLabel');
        $this->assertEquals('<label for="foo" class="test">bar</label>', $method->invokeArgs($layout, [$element]));
    }

    public function test_rendereLabel_empty()
    {
        $element = new \Enjoys\Forms\Elements\Text(
            'foo'
        );
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderLabel');
        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
    }

    public function test_renderDescription()
    {
        $element = new \Enjoys\Forms\Elements\Text(
               'foo'
        );
        $element->setDescription('desc');
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderDescription');
        $this->assertEquals('<small>desc</small>', $method->invokeArgs($layout, [$element]));
    }

    public function test_renderDescription2()
    {
        $element = new \Enjoys\Forms\Elements\Text(
             'foo'
        );
        $element->setDescription('desc');
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderDescription');
        $this->assertEquals('<div>desc</div>', $method->invokeArgs($layout, [$element, 'div']));
    }

    public function test_renderDescription3()
    {
        $element = new \Enjoys\Forms\Elements\Text(
              'foo'
        );
        $element->setDescription('desc');
        $element->addClass('test', \Enjoys\Forms\Form::ATTRIBUTES_DESC);
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderDescription');
        $this->assertEquals('<small class="test">desc</small>', $method->invokeArgs($layout, [$element]));
    }

    public function test_renderDescription_empty()
    {
        $element = new \Enjoys\Forms\Elements\Text(
            'foo'
        );
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderDescription');
        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
    }

    public function test_renderValidation()
    {
        $element = new \Enjoys\Forms\Elements\Text(
              'foo'
        );
        $element->setRuleError('error');
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderValidation');
        $this->assertEquals('<div>error</div>', $method->invokeArgs($layout, [$element]));
    }

    public function test_renderValidation_empty()
    {
        $element = new \Enjoys\Forms\Elements\Text(
             'foo'
        );
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderValidation');
        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
    }

    public function test_renderBody()
    {
$this->markTestIncomplete();
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderBody');

        $element = new \Enjoys\Forms\Elements\Text(
               'foo'
        );
        $this->assertEquals('<input type="text" id="foo" name="foo">', $method->invokeArgs(
                        new \Enjoys\Forms\Renderer\LayoutBase($element),
                        [$element])
        );

        $element = new \Enjoys\Forms\Elements\Textarea(
                'foo'
        );
        $this->assertEquals('<textarea id="foo" name="foo"></textarea>', $method->invokeArgs(
                        new \Enjoys\Forms\Renderer\LayoutBase($element),
                        [$element]
        ));
        
        $element = new \Enjoys\Forms\Elements\Button(
            'foo'
        );
        $this->assertEquals('<button id="foo" name="foo"></button>', $method->invokeArgs(
                        new \Enjoys\Forms\Renderer\LayoutBase($element),
                        [$element]
        ));
        
        $element = new \Enjoys\Forms\Elements\Header(
            'foo'
        );
        $this->assertEquals('foo', $method->invokeArgs(
                        new \Enjoys\Forms\Renderer\LayoutBase($element),
                        [$element]
        ));
        
        $element = new \Enjoys\Forms\Elements\Option(
              'foo', 'bar'
        );
        $this->assertEquals('<option id="foo" value="foo">bar</option>', $method->invokeArgs(
                        new \Enjoys\Forms\Renderer\LayoutBase($element),
                        [$element]
        ));
        
        $element = new \Enjoys\Forms\Elements\Captcha(
              
        );
        $this->assertStringContainsString('<img src="data:image/jpeg;base64', $method->invokeArgs(
                        new \Enjoys\Forms\Renderer\LayoutBase($element),
                        [$element]
        ));
    }
    
    public function test_render()
    {
        $element = new \Enjoys\Forms\Elements\Text(
             'foo'
        );
        $element->setLabel('bar')->setDescription('desc')->setRuleError('error');
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
      
        $this->assertEquals('<label for="foo">bar</label><input type="text" id="foo" name="foo"><small>desc</small><div>error</div>', $layout->render());
    }    
}
