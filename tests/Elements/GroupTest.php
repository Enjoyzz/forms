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

namespace Tests\Enjoys\Forms\Elements;

/**
 * Description of GroupTest
 *
 * @author Enjoys
 */
class GroupTest extends \PHPUnit\Framework\TestCase
{

    public function test_init_group()
    {
        $FormDefaults = new \Enjoys\Forms\FormDefaults([]);
        $g = new \Enjoys\Forms\Elements\Group($FormDefaults, 'group1', [
            new \Enjoys\Forms\Elements\Text($FormDefaults, 'foo', 'bar')
        ]);
        $this->assertInstanceOf('\Enjoys\Forms\Element', $g->getElements()['foo']);
    }

    public function test_invalid_element()
    {
        $this->expectException('\Enjoys\Forms\Exception\ExceptionElement');
        (new \Enjoys\Forms\Elements\Group(new \Enjoys\Forms\FormDefaults([]), 'group1'))
                ->invalid();
    }
}
