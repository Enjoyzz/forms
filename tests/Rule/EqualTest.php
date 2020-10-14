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

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Description of EqualTest
 *
 * @author deadl
 */
class EqualTest extends TestCase
{

    /**
     * @dataProvider data_for_test_validate
     */
    public function test_validate($type, $name, $request, $rule, $expect)
    {
        $class = "Enjoys\Forms\Elements\\" . $type;
        $text = new $class(new \Enjoys\Forms\FormDefaults([]), $name);
        $text->initRequest(new \Enjoys\Forms\Http\Request($request));
        $text->addRule('equal', null, $rule);
        $this->assertEquals($expect, Validator::check([$text]));
    }

    public function data_for_test_validate()
    {
        return [
            ['Text', 'foo', ['foo' => 'test'], ['test'], true],
            ['Text', 'foo', ['foo' => 'valid'], ['test', 'valid'], true],
            ['Text', 'foo', ['foo' => ['2']], ['test', 2], true],
            ['Text', 'foo', ['foo' => ['test']], ['test', 2], true],
            ['Checkbox', 'bar[][][]', ['bar' => ['test']], ['test', 2], true],
            ['Checkbox', 'bar[][][]', ['bar' => [[['failtest']]]], ['test', 2], false], //
            ['Checkbox', 'foo', ['foo' => ['valid']], ['test', 'valid'], true],
            ['Checkbox', 'foo', ['foo' => ['invalid']], ['test', 'valid'], false],
            ['Checkbox', 'foo', ['foo' => [0]], [1], false],
            ['Checkbox', 'foo', ['foo' => [1, 2]], [1], false], //
            ['Checkbox', 'foo', ['foo' => [1, 3]], [1, 2, 3], true],
            ['Text', 'name', ['name' => 'fail'], ['test'], false],
        ];
    }
}
