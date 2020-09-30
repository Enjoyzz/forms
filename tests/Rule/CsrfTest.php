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

namespace Tests\Enjoys\Forms\Rule;

/**
 * Description of CsrfTest
 *
 * @author deadl
 */
class CsrfTest extends \PHPUnit\Framework\TestCase {
    
    use \Tests\Enjoys\Forms\Reflection;
    
    public function test_create_rule() {
        $obj = new \Enjoys\Forms\Rule\Csrf();
        $method = $this->getPrivateMethod('\Enjoys\Forms\Rule\Csrf', 'getMessage');
        $this->assertStringContainsString('CSRF Attack detected', $method->invoke($obj));
    }
    
    public function test_validate() {
        $csrf_key = 'test';
        $hash = crypt($csrf_key);
        $element = new \Enjoys\Forms\Elements\Hidden('token', $hash);
        unset($_POST);
        $_POST['token'] = $hash;
        $obj = new \Enjoys\Forms\Rule\Csrf(null, [
            'csrf_key' => $csrf_key
        ]);

        //$this->assertSame('d',$obj);
        $this->assertTrue($obj->validate($element));
     //   $this->expectException(\Enjoys\Forms\Exception::class);
    }
    
    public function test_non_validate() {
        $csrf_key = 'test';
        $hash = crypt($csrf_key);
        $element = new \Enjoys\Forms\Elements\Hidden('token', $hash);
        unset($_POST);
        $_POST['token'] = 'faketoken';
        $obj = new \Enjoys\Forms\Rule\Csrf(null, [
            'csrf_key' => $csrf_key
        ]);

        //$this->assertSame('d',$obj);
        $this->assertFalse($obj->validate($element));
     //   $this->expectException(\Enjoys\Forms\Exception::class);
    }    
    
 
}
