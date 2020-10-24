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

namespace Enjoys\Forms;

use Enjoys\Forms\Elements;
use Enjoys\Forms\Exception;
use Enjoys\Forms\Traits;

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
    use Traits\Attributes;
    use Traits\Request;
    use \Enjoys\Traits\Options;

    private const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];
    public const _TOKEN_CSRF_ = '_token_csrf';
    public const _TOKEN_SUBMIT_ = '_token_submit';
    public const _FLAG_FORMMETHOD_ = '_form_method';
    public const ATTRIBUTES_DESC = '_desc_attributes_';
    public const ATTRIBUTES_VALIDATE = '_validate_attributes_';
    public const ATTRIBUTES_LABEL = '_label_attributes_';
    public const ATTRIBUTES_FIELDSET = '_fieldset_attributes_';

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
     * @var array Objects stack \Enjoys\Forms\Element
     */
    private array $elements = [];

    /**
     *
     * @var string
     */
    private string $renderer = 'defaults';

    /**
     *
     * @var FormDefaults
     */
    private FormDefaults $formDefaults;

    /**
     *
     * @var string 
     */
    private string $tockenSubmit = '';

    /**
     *
     * @var bool По умолчанию форма не отправлена
     */
    private bool $formSubmitted = false;

    /**
     * @static int
     */
    private static int $counterForms = 0;

    /**
     *
     * @var int Счетчик форм на странице 
     */
    private int $cntForm = 0;

    /**
     * $form = new Form([
     *      'name' => 'myname',
     *      'action' => 'action.php'
     *      'method' => 'post'
     *      'defaults' => [],
     * 
     * ]);
     * @param array $options
     * @param \Enjoys\Forms\Interfaces\Request $request
     */
    public function __construct(array $options = [], Interfaces\Request $request = null)
    {
        $this->cntForm = ++self::$counterForms;
        $this->initRequest($request);
        $this->tockenSubmit = md5(\json_encode($options) . $this->cntForm);

        $this->initTockenSubmit();
        $this->checkFormSubmitted();

        $this->setOptions($options);

        if (!isset($this->formDefaults)) {
            $this->setDefaults([]);
        }
    }

    public function __destruct()
    {
        static::$counterForms = 0;
    }

    public function getFormCount()
    {
        return $this->cntForm;
    }

    private function initTockenSubmit()
    {
        $this->addElement(new Elements\Hidden(new FormDefaults([]), self::_TOKEN_SUBMIT_, $this->tockenSubmit), true);
    }

    private function checkFormSubmitted()
    {
        $method = $this->request->getMethod();
        if ($this->request->$method(self::_TOKEN_SUBMIT_, null) == $this->tockenSubmit) {
            $this->formSubmitted = true;
        }
    }

    /**
     * @param string $method
     * @return void
     */
    private function setMethod(?string $method = null): void
    {
        if (in_array(\strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = \strtoupper($method);
        }
        $this->setAttribute('method', $this->method);

        if (in_array($this->getMethod(), ['POST'])) {
            $this->csrf();
        }
    }

    /**
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set \Enjoys\Forms\FormDefaults $formDefaults 
     * @param array $defaultsData
     * @return \self
     */
    private function setDefaults(array $defaultsData): self
    {
        if ($this->isSubmitted()) {
            $defaultsData = [];
            $method = $this->request->getMethod();

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
     * @return FormDefaults
     */
    public function getFormDefaults(): FormDefaults
    {
        return $this->formDefaults;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->formSubmitted;
    }

    /**
     * Включает защиту от CSRF.
     * Сross Site Request Forgery — «Подделка межсайтовых запросов», также известен как XSRF
     * @param <type> $flag true or false
     */
    private function csrf($flag = true)
    {
        if (!in_array($this->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH']) || $flag === false) {
            $this->removeElement(self::_TOKEN_CSRF_);
            return $this;
        }

        /**
         * @todo поменять session_id() на Session::getSessionId() 
         */
        $csrf_key = '#$' . session_id();
        $hash = crypt($csrf_key, '');
        $csrf = new Elements\Hidden(new FormDefaults([]), self::_TOKEN_CSRF_, $hash);
        $csrf->addRule('csrf', 'CSRF Attack detected', [
            'csrf_key' => $csrf_key]);
        $this->addElement($csrf, true);

        return $this;
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
    private function setName(?string $name = null): self
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
    private function setAction(?string $action = null): self
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
    private function addElement(Element $element, $rewrite = false): self
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
    public function display(array $options = [])
    {

        $rendererName = \ucfirst($this->renderer);
        $renderer = '\\Enjoys\\Forms\\Renderer\\' . $rendererName . '\\' . $rendererName;

        if (!class_exists($renderer)) {
            throw new Exception\ExceptionRenderer("Class <b>{$renderer}</b> not found");
        }
        return new $renderer($this, $options);
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
     * @method Elements\Group group(string $title = null, array $elements = null)
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
        if (!$this->isSubmitted()) {
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
    public function file(string $name, string $title = null): Elements\File
    {
        $element = new Elements\File($this->formDefaults, $name, $title);
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setMethod('post');
        $this->setMaxFileSize(self::phpIniSize2bytes(ini_get('upload_max_filesize')), false);
        $this->addElement($element);
        return $element;
    }

    /**
     * 
     * @todo перенести в другой проект
     */
    static function phpIniSize2bytes($size_original): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size_original); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size_original); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return (int) round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return (int) round($size);
        }
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
            $this->addElement(new Elements\Hidden($this->formDefaults, 'MAX_FILE_SIZE', (string) $bytes));
        }
    }
}
