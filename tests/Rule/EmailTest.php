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

/**
 * Description of EmailTest
 *
 * @author deadl
 */
class EmailTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider data_for_test_validate
     */
    public function test_validate($name, $request, $expect)
    {
        $text = new \Enjoys\Forms\Elements\Text(new \Enjoys\Forms\DefaultsHandler([]), $name);
        $text->initRequest(new \Enjoys\Forms\Http\Request($request));
        $text->addRule(\Enjoys\Forms\Rules::EMAIL);
        $this->assertEquals($expect, \Enjoys\Forms\Validator::check([$text]));
    }

    public function data_for_test_validate()
    {
        return [
            ['foo', ['foo' => 'test@test.com'], true],
            ['foo', ['foo' => 'test@localhost'], false],
            ['foo', ['foo' => '    '], true],
        ];
    }
}
