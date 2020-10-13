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
use Enjoys\Forms\Traits;
use Enjoys\Helpers\Math;

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

    use Traits\Attributes,
        Traits\Request;

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
    public function __construct(string $method = null, string $action = null, Interfaces\Request $request = null)
    {
        $this->initRequest($request);
        $this->formCount = ++static::$counterForms;

        $this->setMethod($method);

        if (!is_null($action)) {
            $this->setAction($action);
        }
        $this->setDefaults([]);
    }

    public function __destruct()
    {
        static::$counterForms = 0;
    }

    /**
     * 
     * @param string|null $method
     * @return void
     */
    private function setMethod(?string $method): void
    {
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
        $this->addElement(new Elements\Hidden(new FormDefaults([]), self::_TOKEN_SUBMIT_, $this->token_submit), true);

        $this->checkSubmittedFrom();
    }

    /**
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function getFormCount()
    {
        return $this->formCount;
    }

    private function generateTokenSubmit()
    {
        $this->token_submit = md5($this->getAction() . $this->getFormCount() . $this->getMethod());
    }

    public function setDefaults(array $defaultsData)
    {
        if ($this->isSubmited()) {
            $defaultsData = [];
            $method = \strtolower($this->getMethod());

            //записываем флаг/значение каким методом отправлена форма
            $defaultsData[Form::_FLAG_FORMMETHOD_] = $method;


            foreach ($this->request->$method() as $key => $items) {
                if (!in_array($key, [self::_TOKEN_CSRF_, self::_TOKEN_SUBMIT_])) {
                    $defaultsData[$key] = $items;
                }
            }
        }
        $this->formDefaults = new FormDefaults($defaultsData);
        return $this;
    }

    /**
     * 
     * @return \Enjoys\Forms\FormDefaults
     */
    public function getFormDefaults(): FormDefaults
    {
        return $this->formDefaults;
    }

    /**
     * 
     * @return bool
     */
    public function isSubmited(): bool
    {
        return $this->submited_form;
    }

    /**
     * Включает защиту от CSRF.
     * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
     * @param <type> $flag true or false
     */
    public function csrf($flag = true)
    {
        if (!in_array($this->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH']) || $flag === false) {
            $this->removeElement(self::_TOKEN_CSRF_);
            return $this;
        }



        // if (!$this->elementExists(self::_TOKEN_CSRF_)) {
        $csrf_key = '#$' . session_id();
        $hash = crypt($csrf_key);
        $csrf = new Elements\Hidden(new FormDefaults([]), self::_TOKEN_CSRF_, $hash);
        $csrf->addRule('csrf', 'CSRF Attack detected', [
            'csrf_key' => $csrf_key]);

        $this->addElement($csrf, true);
        //hash_equals($request->post('_token_csrf'), crypt($form->getCsrfKey(), $request->post('_token_csrf')))
        //$this->addRule(self::$CSRFField, 'CSRF Attack detected', 'csrf', $hash);
        // }
        return $this;
    }

    private function checkSubmittedFrom()
    {
        $method = \strtolower($this->getMethod());
        //dump($method);
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    public function setName(?string $name = null): self
    {
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
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     *
     * @param string $action
     * @return $this
     */
    public function setAction(?string $action = null): self
    {
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
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     *
     * @param Element $element
     * @return \self
     */
    public function addElement(Element $element, $rewrite = false): self
    {
        if ($rewrite === false && $this->elementExists($element->getName())) {
            throw new Exception\ExceptionElement('Элемент c именем ' . $element->getName() . ' (' . \get_class($element) . ') уже был установлен');
        }
        $element->initRequest($this->request);
        $this->elements[$element->getName()] = $element;
        return $this;
    }

    public function removeElement($elementName): self
    {
        if ($this->elementExists($elementName)) {
            unset($this->elements[$elementName]);
        }

        return $this;
    }

    private function elementExists($name): bool
    {
        return isset($this->elements[$name]);
    }

    /**
     *
     * @param string $renderer
     * @return $this
     */
    public function setRenderer(string $renderer): self
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     *
     * @return Renderer
     * @throws Exception
     */
    public function display()
    {

        $renderer = '\\Enjoys\\Forms\\Renderer\\' . \ucfirst($this->renderer);

        if (!class_exists($renderer)) {
            throw new Exception\ExceptionRenderer("Class <b>{$renderer}</b> not found");
        }
        return new $renderer($this);
    }

    /**
     * @method Elements\Text text(string $name, string $title = null)
     * @method Elements\Hidden hidden(string $name, string $value = null)
     * @method Elements\Password password(string $name, string $title = null)
     * @method Elements\Submit submit(string $name, string $title = null)
     * @method Elements\Header header(string $title = null)
     * @method Elements\Color color(string $name, string $title = null)
     * @method Elements\Date date(string $name, string $title = null)
     * @method Elements\Datetime datetime(string $name, string $title = null)
     * @method Elements\Datetimelocal datetimelocal(string $name, string $title = null)
     * @method Elements\Email email(string $name, string $title = null)
     * @method Elements\Number number(string $name, string $title = null)
     * @method Elements\Range range(string $name, string $title = null)
     * @method Elements\Search search(string $name, string $title = null)
     * @method Elements\Tel tel(string $name, string $title = null)
     * @method Elements\Time time(string $name, string $title = null)
     * @method Elements\Url url(string $name, string $title = null)
     * @method Elements\Month month(string $name, string $title = null)
     * @method Elements\Week week(string $name, string $title = null)
     * @method Elements\Textarea textarea(string $name, string $title = null)
     * @method Elements\Select select(string $name, string $title = null)
     * @method Elements\Button button(string $name, string $title = null)
     * @method Elements\Datalist datalist(string $name, string $title = null)
     * @method Elements\Checkbox checkbox(string $name, string $title = null)
     * @method Elements\Image image(string $name, string $title = null)
     * @method Elements\Radio radio(string $name, string $title = null)
     * @method Elements\Reset reset(string $name, string $title = null)
     * @method Elements\Captcha captcha(string $captchaName = null, string $message = null)
     *
     * @mixin Element
     */
    public function __call(string $name, array $arguments)
    {


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
    public function validate()
    {
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
    public function file(string $name, string $title = null): \Enjoys\Forms\Elements\File
    {
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
    public function setMaxFileSize(int $bytes, $removeElement = true)
    {
        if ($removeElement === true) {
            $this->removeElement('MAX_FILE_SIZE');
        }
        if (!$this->elementExists('MAX_FILE_SIZE')) {
            $this->addElement(new Elements\Hidden($this->formDefaults, 'MAX_FILE_SIZE', $bytes));
        }
    }

}
