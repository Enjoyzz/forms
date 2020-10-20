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
class LayoutBaseTest extends \PHPUnit\Framework\TestCase
{
    use \Tests\Enjoys\Forms\Reflection;

    public function test_rendereLabel()
    {
        $element = new \Enjoys\Forms\Elements\Text(
                new \Enjoys\Forms\FormDefaults([]), 'foo', 'bar'
        );
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderLabel');
        $this->assertEquals('<label for="foo">bar</label>', $method->invokeArgs($layout, [$element]));
    }
    public function test_rendereLabel2()
    {
        $element = new \Enjoys\Forms\Elements\Text(
                new \Enjoys\Forms\FormDefaults([]), 'foo', 'bar'
        );
        $element->addClass('test', \Enjoys\Forms\Form::ATTRIBUTES_LABEL);
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderLabel');
        $this->assertEquals('<label for="foo" class="test">bar</label>', $method->invokeArgs($layout, [$element]));
    }
    public function test_rendereLabel_empty()
    {
        $element = new \Enjoys\Forms\Elements\Text(
                new \Enjoys\Forms\FormDefaults([]), 'foo'
        );
        $layout = new \Enjoys\Forms\Renderer\LayoutBase($element);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Renderer\LayoutBase::class, 'renderLabel');
        $this->assertEquals('', $method->invokeArgs($layout, [$element]));
    }
}
