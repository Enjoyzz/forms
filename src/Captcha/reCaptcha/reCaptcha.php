<?php

declare(strict_types=1);

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

namespace Enjoys\Forms\Captcha\reCaptcha;

/**
 * Description of reCaptcha
 *
 * @author deadl
 */
class reCaptcha implements \Enjoys\Forms\Interfaces\Captcha
{

    use \Enjoys\Traits\Options,
        \Enjoys\Traits\Request;

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
    private $element;

    public function __construct($element, $message = null)
    {
        $this->element = $element;
        $this->element->setName('recaptcha2');
        $this->element->addRule('captcha', $message);
    }

    public function renderHtml(): string
    {
        $html = "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
        if ($this->element->isRuleError()) {
            $html .= "<p style=\"color: red\">{$this->element->getRuleErrorMessage()}</p>";
        }
        $html .= "<div class=\"g-recaptcha\" data-sitekey=\"{$this->getOption('publickey', $this->getOption('publickey', $this->publicKey))}\"> </div>";
        return $html;
    }

    public function validate()
    {

        $request = new \Enjoys\Forms\Http\Request();

        $client = $this->getOption('httpClient', $this->getGuzzleClient());

        $data = array(
            'secret' => $this->getOption('privatekey', $this->getOption('privatekey', $this->privateKey)),
            'response' => $request->post('g-recaptcha-response', $request->get('g-recaptcha-response'))
        );



        $response = $client->request('POST', $this->getOption('verify_url', $this->verifyUrl), [
            'form_params' => $data
        ]);

        $responseBody = json_decode($response->getBody()->getContents());


        if ($responseBody->success === false) {
            $errors = [];
            foreach ($responseBody->{'error-codes'} as $error) {
                $errors[] = $this->errorCodes[$error];
            }
            $this->element->setRuleError(implode(', ', $errors));
            return false;
        }
        return true;
    }

    public function setOptions(array $options = [])
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);

            switch ($key) {
                case 'language':
                    $this->setLanguage($value);
                    break;
                default:
                    break;
            }
        }
    }

    private function setLanguage($lang)
    {
        $file_language = __DIR__ . '/lang/' . \strtolower($lang) . '.php';

        if (file_exists($file_language)) {
            $this->errorCodes = include $file_language;
        }
    }

    private function getGuzzleClient()
    {
        return new \GuzzleHttp\Client();
    }

}
