<?php

namespace Tests\Enjoys\Forms\Captcha\reCaptcha;


class reCaptchaTest
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
        $recaptcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $captcha = new \Enjoys\Forms\Elements\Captcha($recaptcha);
        $this->assertInstanceOf('\Enjoys\Forms\Elements\Captcha', $captcha);
    }

    public function test_init_add_rule()
    {

        $recaptcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $captcha = new \Enjoys\Forms\Elements\Captcha($recaptcha);
        $this->assertCount(1, $captcha->getRules());
    }

    public function test_render()
    {

        $recaptcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $captcha = new \Enjoys\Forms\Elements\Captcha($recaptcha);
        $this->assertStringContainsString('<script src="https://www.google.com/recaptcha/api.js" async defer></script><div class="g-recaptcha" data-sitekey="6LdUGNEZAAAAANA5cPI_pCmOqbq-6_srRkcGOwRy"> </div>', $this->toOneString($captcha->renderHtml()));
    }

    public function test_validate_success()
    {

        $responseBody = \json_encode([
            'success' => true,
        ]);

        $captcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $captcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody)
        ]);

        $captcha_element = new \Enjoys\Forms\Elements\Captcha($captcha);

        $this->assertTrue($captcha_element->validate());
    }

    public function test_validate_false()
    {
        $responseBody = \json_encode([
            'success' => false,
            'error-codes' =>
            [
                0 => 'missing-input-response',
            ],
        ]);
        $recaptcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $recaptcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody)
        ]);


        $captcha = new \Enjoys\Forms\Elements\Captcha($recaptcha);

        $this->assertFalse($captcha->validate());
        $this->assertEquals('The response parameter is missing.', $captcha->getRuleErrorMessage());
    }

    public function test_validate_false_render()
    {

        $responseBody = \json_encode([
            'success' => false,
            'error-codes' =>
            [
                0 => 'missing-input-response',
                1 => 'invalid-input-secret'
            ],
        ]);
        $recaptcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $recaptcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody)
        ]);
        $captcha = new \Enjoys\Forms\Elements\Captcha($recaptcha);



        $captcha->validate();
        $captcha->renderHtml();
        $this->assertEquals('The response parameter is missing., The secret parameter is invalid or malformed.', $captcha->getRuleErrorMessage());
    }

    public function test_validate_false_render_width_setLanguage()
    {
        $recaptcha = new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha();
        $responseBody = \json_encode([
            'success' => false,
            'error-codes' =>
            [
                0 => 'missing-input-response',
                1 => 'invalid-input-secret'
            ],
        ]);

        $recaptcha->setOptions([
            'httpClient' => $this->getHttpClient('text/plain', $responseBody),
            'language' => 'ru'
        ]);
        $captcha = new \Enjoys\Forms\Elements\Captcha($recaptcha);


        $captcha->validate();
        $captcha->renderHtml();
        $this->assertEquals('Параметр ответа отсутствует., Секретный параметр является недопустимым или искаженным.', $captcha->getRuleErrorMessage());
    }
}
