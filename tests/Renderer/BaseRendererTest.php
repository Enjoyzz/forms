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

namespace Tests\Enjoys\Forms\Renderer;

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\BaseRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Description of BaseRendererTest
 *
 * @author Enjoys
 */
class BaseRendererTest extends TestCase
{
    public function test_render()
    {
        $form = new Form(['method'=>'get']);
        $form->text('foo');
        $form->color('bar');
        $render = new BaseRenderer();
        $render->setForm($form);
        
        $result = $render->render();
        $this->assertStringContainsString('<form method="GET">', $result);
        $this->assertStringContainsString('<input type="hidden" name="_token_submit" value="', $result);
        $this->assertStringContainsString('<input type="text" id="foo" name="foo">', $result);
        $this->assertStringContainsString('<input type="color" id="bar" name="bar">', $result);
    }
}
