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

namespace Enjoys\Forms;

use \Enjoys\Helpers\Math;

/**
 * 
 * Class Forms
 * 
 * 
 * @author Enjoys
 * 
 */
class Forms {

    use Traits\Attributes,
        \Enjoys\Traits\Request;

    const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];
    const _TOKEN_CSRF_ = '_token_csrf';
    const _TOKEN_SUBMIT_ = '_token_submit';

    /**
     *
     * @var string|null   
     */
    private ?string $name = null;

    /**
     *
     * @var string POST|GET 
     */
    private $method = 'GET';

    /**
     *
     * @var string|null   
     */
    private ?string $action = null;

    /**
     *
     * @var array objects \Enjoys\Forms\Element 
     */
    private array $elements = [];

    /**
     *
     * @var string
     */
    private string $renderer = 'defaults';

    /**
     *
     * @var array
     */
    private $defaults = [];
    private string $token_submit = '';
    private bool $submited_form = false;

    /**
     * @param string $method
     * @param string $action
     */
    public function __construct(string $method = null, string $action = null) {

        $this->initRequest();


        if (!is_null($method)) {
            $this->setMethod($method);
        }

        if (!is_null($action)) {
            $this->setAction($action);
        }
        
        $this->setTokenSubmit();
        $this->addElement(new Elements\Hidden(self::_TOKEN_SUBMIT_, $this->token_submit));

        $this->checkSubmittedFrom();        
    }

    private function setTokenSubmit() {
        $this->token_submit = md5($this->getName() . $this->getAction());
    }
 
    public function setDefaults(array $defaults) {
        $this->defaults = $defaults;
        if ($this->isSubmited()) {
            $this->defaults = [];
            $method = \strtolower($this->getMethod());

            foreach ($this->request->$method() as $key => $items) {
                $this->defaults[$key] = $items;
            }
        }
        return $this;
    }

    public function isSubmited(): bool {
        return $this->submited_form;
    }

    private $csrf_key;

    public function getCsrfKey() {
        return $this->csrf_key;
    }

    /**
     * Включает защиту от CSRF.
     * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
     * @param <type> $flag true or false
     */
    public function csrf($flag = true) {

        if (!in_array($this->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $this->removeElement(self::_TOKEN_CSRF_);
            return $this;
        }


        if ($flag === false) {
            $this->removeElement(self::_TOKEN_CSRF_);
            return $this;
        }

        // if (!$this->elementExists(self::_TOKEN_CSRF_)) {
        $this->csrf_key = '#$' . session_id();
        $hash = crypt($this->csrf_key);
        $this->addElement(new Elements\Hidden(self::_TOKEN_CSRF_, $hash), true);
        //hash_equals($request->post('_token_csrf'), crypt($form->getCsrfKey(), $request->post('_token_csrf')))
        //$this->addRule(self::$CSRFField, 'CSRF Attack detected', 'csrf', $hash);
        // }
        return $this;
    }

    private function checkSubmittedFrom() {
        $method = \strtolower($this->getMethod());
        if ($this->request->$method(self::_TOKEN_SUBMIT_, null) == $this->token_submit) {
            $this->submited_form = true;
            return;
        }
        $this->submited_form = false;
        return;
    }

    /**
     * 
     * @return string
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     * @return $this
     */
    public function setName(?string $name): self {
        $this->name = $name;
        if (!is_null($name)) {
            $this->setAttribute('name', $this->name);
        }
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getAction(): ?string {
        return $this->action;
    }

    /**
     *
     * @param string $action
     * @return $this
     */
    public function setAction(?string $action): self {
        $this->action = $action;
        $this->setAttribute('action', $this->getAction());
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * 
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): self {
        if (in_array(\strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = \strtoupper($method);
        }
        $this->setAttribute('method', $this->method);

        if (in_array($this->getMethod(), ['POST'])) {
            $this->csrf();
        }

        $this->checkSubmittedFrom();

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getElements(): array {
        return $this->elements;
    }

    /**
     * 
     * @param \Enjoys\Forms\Element $element
     * @return \self
     */
    public function addElement(Element $element, $rewrite = false): self {
        if ($rewrite === false && $this->elementExists($element->getName())) {
            throw new Exception('Элемент c именем ' . $element->getName() . ' (' . \get_class($element) . ') уже был установлен');
        }
        $this->elements[$element->getName()] = $element;
        return $this;
    }

    public function removeElement($elementName): self {
        if ($this->elementExists($elementName)) {
            unset($this->elements[$elementName]);
        }

        return $this;
    }

    private function elementExists($name): bool {
        return isset($this->elements[$name]);
    }

    /**
     * 
     * @param string $renderer
     * @return $this
     */
    public function setRenderer(string $renderer): self {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * 
     * @return Renderer
     * @throws Exception
     */
    public function display() {

        $renderer = '\\Enjoys\\Forms\\Renderer\\' . \ucfirst($this->renderer);

        if (!class_exists($renderer)) {
            throw new Exception("Class <b>{$renderer}</b> not found");
        }
        return new $renderer($this);
    }

    /**
     * @method Elements\Text text(string $name, string $title)
     * @method Elements\Hidden hidden(string $name, string $value)
     * @method Elements\Password password(string $name, string $title)
     * @method Elements\Submit submit(string $name, string $title)
     * @method Elements\Header header(string $title)
     * @method Elements\Color color(string $name, string $title)
     * @method Elements\Date date(string $name, string $title)
     * @method Elements\Datetime datetime(string $name, string $title)
     * @method Elements\Datetimelocal datetimelocal(string $name, string $title)
     * @method Elements\Email email(string $name, string $title)
     * @method Elements\Number number(string $name, string $title)
     * @method Elements\Range range(string $name, string $title)
     * @method Elements\Search search(string $name, string $title)
     * @method Elements\Tel tel(string $name, string $title)
     * @method Elements\Time time(string $name, string $title)
     * @method Elements\Url url(string $name, string $title)
     * @method Elements\Month month(string $name, string $title)
     * @method Elements\Week week(string $name, string $title)
     * @method Elements\Textarea textarea(string $name, string $title)
     * @method Elements\Select select(string $name, string $title)
     * @method Elements\Button button(string $name, string $title)
     * @method Elements\Datalist datalist(string $name, string $title)
     * @method Elements\Checkbox checkbox(string $name, string $title)
     * @method Elements\Image image(string $name, string $title)
     * @method Elements\Radio radio(string $name, string $title)
     * @method Elements\Reset reset(string $name, string $title)
     * @todo Captcha
     *  
     * @mixin Element
     */
    public function __call(string $name, array $arguments) {


        $class_name = '\Enjoys\\Forms\Elements\\' . ucfirst($name);
        if (!class_exists($class_name)) {
            throw new Exception("Class <b>{$class_name}</b> not found at line in Forms\Elements");
        }
        /** @var Element $element */
        $element = new $class_name(...$arguments);
        $element->setDefault($this->defaults);
        $this->addElement($element);
        return $element;
    }

    /**
     * 
     * @param string $name
     * @param string $title
     * @return \Enjoys\Forms\Elements\File
     */
    public function file(string $name, string $title = null): \Enjoys\Forms\Elements\File {
        $element = new \Enjoys\Forms\Elements\File($name, $title);
        $this->addAttribute('enctype', 'multipart/form-data');
        $this->setMethod('post');
        $this->setMaxFileSize(Math::shorthandbytes2int(ini_get('upload_max_filesize')), false);
        $this->addElement($element);
        return $element;
    }

    public function setMaxFileSize(int $bytes, $removeElement = true) {
        if ($removeElement === true) {
            $this->removeElement('MAX_FILE_SIZE');
        }
        if (!$this->elementExists('MAX_FILE_SIZE')) {
            $this->addElement(new Elements\Hidden('MAX_FILE_SIZE', $bytes));
        }
    }
    
    public function captcha($captcha = 'defaults') {
        $class_name = \ucfirst($captcha);
        $class = "\Enjoys\Forms\Captcha\\".$class_name."\\".$class_name;
        $element = new $class();
        $this->addElement($element);    
        return $element;
    }

}
