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

namespace Tests\Enjoys\Forms\Captcha\reCaptcha;

/**
 * Class reCaptchaTest
 *
 * @author Enjoys
 */
class reCaptchaTest extends \PHPUnit\Framework\TestCase {

    private function toOneString($multistring) {
        return preg_replace('/\s+/', ' ', $multistring);
    }

    public function test_init() {
        $captcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $this->assertTrue($captcha instanceof \Enjoys\Forms\Captcha\reCaptcha\reCaptcha);
    }

    public function test_init_add_rule() {
        $captcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $this->assertCount(1, $captcha->getRules());
    }

    public function test_render() {
        $captcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $this->assertStringContainsString('<script src="https://www.google.com/recaptcha/api.js" async defer></script><div class="g-recaptcha" data-sitekey="6LdUGNEZAAAAANA5cPI_pCmOqbq-6_srRkcGOwRy"> </div>', $this->toOneString($captcha->renderHtml()));
    }

    public function test_validate() {
 $captcha = $this->getMockBuilder(\Enjoys\Forms\Captcha\reCaptcha\reCaptcha::class)
         ->addMethods(['getGuzzleClient'])
         ->getMock();
 
        $guzzle = $this->getMockBuilder(\GuzzleHttp\Client::class)->addMethods(['getContents'])->getMock();
//        $guzzle->expects($this->once())->method('request')->will($this->returnSelf());
//        $guzzle->expects($this->once())->method('getBody')->will($this->returnSelf());
        $guzzle->expects($this->once())->method('getContents')->will($this->returnValue(\json_encode([
                    'success' => false,
                    'error-codes' =>
                    [
                        0 => 'missing-input-response',
                    ],
        ])));
       
        $captcha->expects($this->once())->method('getGuzzleClient')->willReturn($guzzle);

        $this->assertSame('dfgdfg',$captcha->validate());
        $this->assertFalse($captcha->validate());
    }

}
