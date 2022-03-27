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

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\Http\ServerRequest;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;
use PHPUnit\Framework\TestCase;

/**
 * Class RequiredTest
 *
 * @author Enjoys
 */
class RequiredTest extends TestCase
{

    public function test_required_()
    {

        $element = new Checkbox('name');


        $element->setRequestWrapper(new ServerRequestWrapper(
                        ServerRequestCreator::createFromGlobals(
                                null,
                                null,
                                null,
                                ['name' => [1, 2]]
                        )
        ));
        $element->addRule(Rules::REQUIRED);
        $this->assertTrue(Validator::check([$element]));
    }

    public function test_required_2()
    {
        $element = new Checkbox('name');
        $element->setRequestWrapper(new ServerRequestWrapper(
                        ServerRequestCreator::createFromGlobals(
                                null,
                                null,
                                null,
                                ['name' => []]
                        )
        ));
        $element->addRule(Rules::REQUIRED);
        $this->assertFalse(Validator::check([$element]));
    }

    public function test_required_3()
    {

        $element = new Checkbox('name');
        $element->addRule(Rules::REQUIRED);
        $this->assertFalse(Validator::check([$element]));
    }
}
