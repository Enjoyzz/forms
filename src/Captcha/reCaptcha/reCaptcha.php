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

namespace Enjoys\Forms\Captcha\reCaptcha;

/**
 * Description of reCaptcha
 *
 * @author deadl
 */
class reCaptcha extends \Enjoys\Forms\Element implements \Enjoys\Forms\Interfaces\Captcha {

    use \Enjoys\Traits\Options;

    private $privatekey = '6LdUGNEZAAAAAPPz685RwftPySFeCLbV1xYJJjsk'; //localhost
    private $publickey = '6LdUGNEZAAAAANA5cPI_pCmOqbq-6_srRkcGOwRy'; //localhost
    private $verify_url = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct($rule_message = null) {
        parent::__construct('captcha_defaults');

        $this->addAttribute([
            'type' => 'text',
            'autocomplete' => 'off'
        ]);



        $this->addRule('captcha', $rule_message);
    }

    public function validate($value) {
        $request = new \Enjoys\Base\Request();
        $data = array(
            'secret' => $this->getOption('privatekey', $this->privatekey),
            'response' => $request->post('g-recaptcha-response', $request->get('g-recaptcha-response'))
        );
        
        return true;
    }

    public function renderHtml() {
        $html = "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
        $html .= "<div class=\"g-recaptcha\" data-sitekey=\"{$this->getOption('publickey', $this->publickey)}\"> </div>";
        return $html;
    }

}
