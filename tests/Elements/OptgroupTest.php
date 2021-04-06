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

use PHPUnit\Framework\TestCase;

/**
 * Description of OptgroupTest
 *
 * @author Enjoys
 */
class OptgroupTest extends TestCase
{

    public function test_baseHtml()
    {
        $form = new \Enjoys\Forms\Form();
        $og = $form->optgroup(
                'foo',
                'parentname'
        );
        $this->assertEquals(null, $og->baseHtml());
    }
    
    public function test_setdefaults()
    {
     
        $form = new \Enjoys\Forms\Form();
        $form->setDefaults([
            'select1' => [
                1,3,'b','c'
            ]
        ]);
        $select = $form->select('select1')
                ->setOptgroup('numbers', [1,2,3], [], true)
                ->setOptgroup('alpha', ['a', 'b', 'c'], [], true)
                ->setMultiple()
                ;
        $this->assertNull($select->getElements()[0]->getElements()[0]->getAttribute('selected'));
        $this->assertFalse($select->getElements()[0]->getElements()[1]->getAttribute('selected'));
        $this->assertNull($select->getElements()[0]->getElements()[2]->getAttribute('selected'));
        $this->assertFalse($select->getElements()[1]->getElements()[0]->getAttribute('selected'));
        $this->assertNull($select->getElements()[1]->getElements()[1]->getAttribute('selected'));
        $this->assertNull($select->getElements()[1]->getElements()[2]->getAttribute('selected'));
    }
}
