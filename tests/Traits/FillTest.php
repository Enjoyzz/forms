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

namespace Tests\Enjoys\Forms\Traits;

/**
 * Class FillTest
 *
 * @author Enjoys
 */
class FillTest extends \PHPUnit\Framework\TestCase
{

    use \Tests\Enjoys\Forms\Reflection;

    public function test_setIndexKeyFill()
    {
        $radio = new \Enjoys\Forms\Elements\Radio(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $method = $this->getPrivateMethod(\Enjoys\Forms\Elements\Radio::class, 'setIndexKeyFill');
        $method->invokeArgs($radio, ['value']);
        $radio->fill(['test' => 1, 'foz' => 2, 'baz' => 3]);
        $method = $this->getPrivateMethod(\Enjoys\Forms\Elements\Radio::class, 'getIndexKey');
        $this->assertEquals('value', $method->invoke($radio));

        $this->assertArrayHasKey('test', $radio->getElements());
        $this->assertArrayHasKey('foz', $radio->getElements());
        $this->assertArrayHasKey('baz', $radio->getElements());
    }

    public function test_fill_with_attributes()
    {
        $select = new \Enjoys\Forms\Elements\Select(new \Enjoys\Forms\FormDefaults([], new \Enjoys\Forms\Form()), 'foo');
        $select->fill(['test' => ['title1', [
                    'disabled'
                ]], 'foz' => [2, [
                    'id' => 'newfoz'
                ]], 'baz' => 3]);
        $this->assertEquals(null, $select->getElements()[0]->getAttribute('disabled'));
        $this->assertEquals(false, $select->getElements()[1]->getAttribute('disabled'));
        $this->assertEquals('newfoz', $select->getElements()[1]->getAttribute('id'));
        
        $method = $this->getPrivateMethod(\Enjoys\Forms\Elements\Option::class, 'getParentName');
        $this->assertEquals('foo', $method->invoke($select->getElements()[2]));
        
        $method = $this->getPrivateMethod(\Enjoys\Forms\Elements\Option::class, 'getCounterId');
        $this->assertEquals(1, $method->invoke($select->getElements()[1]));
    }

}
