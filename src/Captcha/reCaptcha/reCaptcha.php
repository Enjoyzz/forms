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

namespace Enjoys\Forms\Captcha\reCaptcha;

use Enjoys\Forms\Captcha\CaptchaBase;
use Enjoys\Forms\Captcha\CaptchaInterface;
use Enjoys\Forms\Element;
use GuzzleHttp\Client;



/**
 * Description of reCaptcha
 *
 * @author Enjoys
 */
class reCaptcha extends CaptchaBase implements CaptchaInterface
{
    private $privateKey = '6LdUGNEZAAAAAPPz685RwftPySFeCLbV1xYJJjsk'; //localhost
    private $publicKey = '6LdUGNEZAAAAANA5cPI_pCmOqbq-6_srRkcGOwRy'; //localhost
    private $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    private $errorCodes = [
        'missing-input-secret' => 'The secret parameter is missing.',
        'invalid-input-secret' => 'The secret parameter is invalid or malformed.',
        'missing-input-response' => 'The response parameter is missing.',
        'invalid-input-response' => 'The response parameter is invalid or malformed.',
        'bad-request' => 'The request is invalid or malformed.',
        'timeout-or-duplicate' => 'The response is no longer valid: either is too old or has been used previously.',
    ];

   

    public function __construct()
    {
        $this->setName('recaptcha2');
        $this->setRuleMessage(null);
    }


    /**
     * 
     * @param \Enjoys\Forms\Element $element
     * @return string
     */
    public function renderHtml(Element $element): string
    {
        $html = "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
        $html .= "<div class=\"g-recaptcha\" data-sitekey=\"{$this->getOption('publickey', $this->getOption('publickey', $this->publicKey))}\"> </div>";
        return $html;
    }

    /**
     * 
     * @param \Enjoys\Forms\Element $element
     * @return bool
     */
    public function validate(Element $element): bool
    {
        $client = $this->getOption('httpClient', $this->getGuzzleClient());

        $data = array(
            'secret' => $this->getOption('privatekey', $this->getOption('privatekey', $this->privateKey)),
            'response' => $this->getRequest()->post('g-recaptcha-response', $this->getRequest()->get('g-recaptcha-response'))
        );



        $response = $client->request('POST', $this->getOption('verify_url', $this->verifyUrl), [
            'form_params' => $data
        ]);

        $responseBody = \json_decode($response->getBody()->getContents());


        if ($responseBody->success === false) {
            $errors = [];
            foreach ($responseBody->{'error-codes'} as $error) {
                $errors[] = $this->errorCodes[$error];
            }
            $element->setRuleError(implode(', ', $errors));
            return false;
        }
        return true;
    }

    /**
     * Used across setOption()
     *
     * @param type $lang
     *
     * @return void
     */
    public function setLanguage($lang): void
    {
        $file_language = __DIR__ . '/lang/' . \strtolower($lang) . '.php';

        if (file_exists($file_language)) {
            $this->errorCodes = include $file_language;
        }
    }
    
 
    private function getGuzzleClient(): Client
    {
        return new Client();
    }
}
