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

use Enjoys\Forms\Exception;
use Enjoys\Forms\Traits\Attributes;
use Enjoys\Helpers\Math;
use Enjoys\Traits\Request;

/**
 *
 * Class Forms
 *
 *
 * @author Enjoys
 *
 */
class Form
{

    use Attributes;

    const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];
    const _TOKEN_CSRF_ = '_token_csrf';
    const _TOKEN_SUBMIT_ = '_token_submit';
    const _FLAG_FORMMETHOD_ = '_form_method';

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
    private FormDefaults $formDefaults;
    private string $token_submit = '';
    private bool $submited_form = false;
    static private int $counterForms = 0;
    private int $formCount = 0;

    /**
     * @param string $method
     * @param string $action
     */
    public function __construct(string $method = null, string $action = null) {

        $this->formDefaults = new FormDefaults([], $this);
        // $this->getRequest();

        $this->formCount = ++static::$counterForms;

        $this->setMethod($method);

        if (!is_null($action)) {
            $this->setAction($action);
        }


        

        

        $this->setFormDefaults($this->formDefaults);
    }

    public function __destruct() {
        static::$counterForms = 0;
        //static::$defaults = [];
    }

    /**
     * 
     * @param string|null $method
     * @return void
     */
    private function setMethod(?string $method): void {
        if (in_array(\strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = \strtoupper($method);
        }
        $this->setAttribute('method', $this->method);

        if (is_null($method)) {
            $this->removeAttribute('method');
        }

        if (in_array($this->getMethod(), ['POST'])) {
            $this->csrf();
        }

        $this->generateTokenSubmit();
        $this->addElement(new Elements\Hidden($this->formDefaults, self::_TOKEN_SUBMIT_, $this->token_submit), true);
        
        $this->checkSubmittedFrom(new Http\Request());
    }

    /**
     *
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    public function getFormCount() {
        return $this->formCount;
    }

    private function generateTokenSubmit() {
        $this->token_submit = md5($this->getAction() . $this->getFormCount() . $this->getMethod());
    }

    public function setFormDefaults(FormDefaults $formDefaults) {

        $this->formDefaults = $formDefaults;

        return $this;
    }

    /**
     * 
     * @return \Enjoys\Forms\FormDefaults
     */
    public function getFormDefaults(): FormDefaults {
        return $this->formDefaults;
    }

    /**
     * 
     * @return bool
     */
    public function isSubmited(): bool {
        return $this->submited_form;
    }

    /**
     * Включает защиту от CSRF.
     * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
     * @param <type> $flag true or false
     */
    public function csrf($flag = true) {
        if (!in_array($this->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH']) || $flag === false) {
            $this->removeElement(self::_TOKEN_CSRF_);
            return $this;
        }



        // if (!$this->elementExists(self::_TOKEN_CSRF_)) {
        $csrf_key = '#$' . session_id();
        $hash = crypt($csrf_key);
        $csrf = new Elements\Hidden(new FormDefaults([], $this), self::_TOKEN_CSRF_, $hash);
        $csrf->addRule('csrf', 'CSRF Attack detected', [
            'csrf_key' => $csrf_key]);

        $this->addElement($csrf, true);
        //hash_equals($request->post('_token_csrf'), crypt($form->getCsrfKey(), $request->post('_token_csrf')))
        //$this->addRule(self::$CSRFField, 'CSRF Attack detected', 'csrf', $hash);
        // }
        return $this;
    }

    private function checkSubmittedFrom(Interfaces\Request $request) {
        $method = \strtolower($this->getMethod());
        //dump($method);
        if ($request->$method(self::_TOKEN_SUBMIT_, null) == $this->token_submit) {
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
    public function setName(?string $name = null): self {
        $this->name = $name;
        $this->setAttribute('name', $this->name);

        if (is_null($name)) {
            $this->removeAttribute('name');
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
    public function setAction(?string $action = null): self {
        $this->action = $action;
        $this->setAttribute('action', $this->getAction());

        if (is_null($action)) {
            $this->removeAttribute('action');
        }

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
     * @param Element $element
     * @return \self
     */
    public function addElement(Element $element, $rewrite = false): self {
        if ($rewrite === false && $this->elementExists($element->getName())) {
            throw new Exception\ExceptionElement('Элемент c именем ' . $element->getName() . ' (' . \get_class($element) . ') уже был установлен');
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
            throw new Exception\ExceptionRenderer("Class <b>{$renderer}</b> not found");
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
     *
     * @mixin Element
     */
    public function __call(string $name, array $arguments) {


        $class_name = '\Enjoys\\Forms\Elements\\' . ucfirst($name);
        if (!class_exists($class_name)) {
            throw new Exception\ExceptionElement("Class <b>{$class_name}</b> not found at line in Forms\Elements");
        }
        //dump($this->formDefaults);
        /** @var Element $element */
        $element = new $class_name($this->formDefaults, ...$arguments);
       // dump($element);
        $this->addElement($element);
        return $element;
    }

    /**
     *
     * @return boolean
     */
    public function validate() {
        if (!$this->isSubmited()) {
            return false;
        }
      //  dump($this->getElements());
        return Validator::check($this->getElements());
    }

    /**
     *
     * @param string $name
     * @param string $title
     * @return \Enjoys\Forms\Elements\File
     */
    public function file(string $name, string $title = null): \Enjoys\Forms\Elements\File {
        $element = new \Enjoys\Forms\Elements\File($this->formDefaults, $name, $title);
        $this->addAttributes('enctype', 'multipart/form-data');
        $this->setMethod('post');
        $this->setMaxFileSize(Math::shorthandbytes2int(ini_get('upload_max_filesize')), false);
        $this->addElement($element);
        return $element;
    }

    /**
     *
     * @param int $bytes
     * @param type $removeElement
     */
    public function setMaxFileSize(int $bytes, $removeElement = true) {
        if ($removeElement === true) {
            $this->removeElement('MAX_FILE_SIZE');
        }
        if (!$this->elementExists('MAX_FILE_SIZE')) {
            $this->addElement(new Elements\Hidden($this->formDefaults, 'MAX_FILE_SIZE', $bytes));
        }
    }

    /**
     * 
     * @param string|null $captcha
     * @param string|null $message
     * @return \Enjoys\Forms\Interfaces\Captcha
     * @throws Exception
     */
//    public function captcha(?string $captcha = null, ?string $message = null): \Enjoys\Forms\Interfaces\Captcha {
//        if (is_null($captcha)) {
//            $captcha = 'Defaults';
//        }
//        $class = "\Enjoys\Forms\Captcha\\" . $captcha . "\\" . $captcha;
//
//        if (!class_exists($class)) {
//            throw new Exception("Class <b>{$class}</b> not found");
//        }
//
//        /** @var \Enjoys\Forms\Interfaces\Captcha $element */
//        $element = new $class($message);
//        $this->addElement($element);
//        return $element;
//    }

}