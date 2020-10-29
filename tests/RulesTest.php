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

namespace Tests\Enjoys\Forms;

/**
 * Description of RuleBaseTest
 *
 * @author deadl
 */
class RulesTest
{

    public function test_setParams_1_0()
    {
        $rules = new \Enjoys\Forms\Rules('message', [1]);
        $this->assertEquals([1], $rules->getParams());
        $this->assertEquals('message', $rules->getMessage());
        $this->assertNull($rules->getParam('test'));
    }

    public function test_setParams_1_1()
    {
        $rules = new \Enjoys\Forms\Rules('message', 'param');
        $this->assertEquals(['param'], $rules->getParams());
    }

    public function test_setParams_1_2()
    {
        $rules = new \Enjoys\Forms\Rules('message', [
            'key_param' => 'value_param'
        ]);
        $this->assertEquals('value_param', $rules->getParam('key_param'));
    }
}
