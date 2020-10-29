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
class reCaptchaTest extends \PHPUnit\Framework\TestCase
{

    private function getHttpClient($contentType, $responseBody, $extraHeaders = [])
    {
        $extraHeaders['Content-Type'] = $contentType;

        $response = $this->getMockBuilder(\GuzzleHttp\Psr7\Response::class)->setMethods(['hasHeader', 'getHeader', 'getBody'])->getMock();
        $response->expects($this->any())->method('hasHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
                    return \array_key_exists($headerName, $extraHeaders);
                }));
        $response->expects($this->any())->method('getHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
                    return [$extraHeaders[$headerName]];
                }));

        $stream = $this->getMockBuilder(\GuzzleHttp\Psr7\Stream::class)->disableOriginalConstructor()->setMethods(['__toString', 'getContents'])->getMock();
        $stream->expects($this->any())->method('__toString')->willReturn($responseBody);
        $response->expects($this->any())->method('getBody')->willReturn($stream);
        $stream->expects($this->any())->method('getContents')->willReturn($responseBody);

        $http = $this->getMockBuilder(\GuzzleHttp\Client::class)->setMethods(['request'])->getMock();
        $http->expects($this->any())->method('request')->will($this->returnCallback(function ($method, $address, array $options) use ($response) {
                    $this->lastRequestOptions = $options;

                    return $response;
                }));

        return $http;
    }

    private function toOneString($multistring)
    {
        return preg_replace('/\s+/', ' ', $multistring);
    }

    public function test_init()
    {
            $this->markTestIncomplete();
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');
        $this->assertInstanceOf('\Enjoys\Forms\Elements\Captcha', $captcha);
    }

    public function test_init_add_rule()
    {
            $this->markTestIncomplete();
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');
        $this->assertCount(1, $captcha->getRules());
    }

    public function test_render()
    {
            $this->markTestIncomplete();
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');
        $this->assertStringContainsString('<script src="https://www.google.com/recaptcha/api.js" async defer></script><div class="g-recaptcha" data-sitekey="6LdUGNEZAAAAANA5cPI_pCmOqbq-6_srRkcGOwRy"> </div>', $this->toOneString($captcha->renderHtml()));
    }

    public function test_validate_success()
    {
            $this->markTestIncomplete();
        $responseBody = \json_encode([
            'success' => true,
        ]);
        $captcha_element = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');
        $captcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha($captcha_element);

        $captcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody)
        ]);

        $this->assertTrue($captcha->validate());
    }

    public function test_validate_false()
    {
            $this->markTestIncomplete();
        $responseBody = \json_encode([
            'success' => false,
            'error-codes' =>
            [
                0 => 'missing-input-response',
            ],
        ]);
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');


        $captcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody)
        ]);

        $this->assertFalse($captcha->validate());
        $this->assertEquals('The response parameter is missing.', $captcha->getRuleErrorMessage());
    }

    public function test_validate_false_render()
    {
            $this->markTestIncomplete();
        $responseBody = \json_encode([
            'success' => false,
            'error-codes' =>
            [
                0 => 'missing-input-response',
                1 => 'invalid-input-secret'
            ],
        ]);
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');


        $captcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody)
        ]);
        $captcha->validate();
        $captcha->renderHtml();
        $this->assertEquals('The response parameter is missing., The secret parameter is invalid or malformed.', $captcha->getRuleErrorMessage());
    }

    public function test_validate_false_render_width_setLanguage()
    {
            $this->markTestIncomplete();
        $responseBody = \json_encode([
            'success' => false,
            'error-codes' =>
            [
                0 => 'missing-input-response',
                1 => 'invalid-input-secret'
            ],
        ]);
        $captcha = new \Enjoys\Forms\Elements\Captcha(new \Enjoys\Forms\DefaultsHandler([]), 'reCaptcha');


        $captcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody),
            'language' => 'ru'
        ]);
        $captcha->validate();
        $captcha->renderHtml();
        $this->assertEquals('Параметр ответа отсутствует., Секретный параметр является недопустимым или искаженным.', $captcha->getRuleErrorMessage());
    }
}
