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
use Enjoys\Forms\Http\RequestInterface;
use Enjoys\Forms\Traits;
use Enjoys\Traits\Options;

/**
 *
 * Class Forms
 *
 *
 * @author Enjoys
 *
 */
final class Form
{
    use Traits\Attributes;
    use Traits\Request;
    use Options;

    private const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];
    public const _TOKEN_CSRF_ = '_token_csrf';
    public const _TOKEN_SUBMIT_ = '_token_submit';
    public const _FLAG_FORMMETHOD_ = '_form_method';
    public const ATTRIBUTES_DESC = '_desc_attributes_';
    public const ATTRIBUTES_VALIDATE = '_validate_attributes_';
    public const ATTRIBUTES_LABEL = '_label_attributes_';
    public const ATTRIBUTES_FIELDSET = '_fieldset_attributes_';

    /**
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
//    private string $renderer = 'defaults';

    /**
     *
     * @var DefaultsHandler
     */
    private DefaultsHandler $defaultsHandler;

    /**
     *
     * @var string 
     */
//    private string $tockenSubmit = '';

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
     * @param RequestInterface $request
     */
    public function __construct(array $options = [], RequestInterface $request = null)
    {
        $this->cntForm = ++self::$counterForms;
        $this->setRequest($request);

        $tockenSubmit = $this->tockenSubmit(md5(\json_encode($options) . $this->cntForm));
        $this->formSubmitted = $tockenSubmit->getSubmitted();

        $this->setOptions($options);
    }

    public function __destruct()
    {
        static::$counterForms = 0;
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
     * @method Elements\File file(string $title = null, array $elements = null)
     *
     * @mixin Element
     */
    public function __call(string $name, array $arguments)
    {
        $class_name = '\Enjoys\\Forms\Elements\\' . ucfirst($name);
        if (!class_exists($class_name)) {
            throw new Exception\ExceptionElement("Class <b>{$class_name}</b> not found");
        }
        /** @var Element $element */
        $element = new $class_name(...$arguments);

        // dump($element);
        $this->addElement($element);
        return $element;
    }

    /**
     *
     * @param Element $element
     * @return \self
     */
    public function addElement(Element $element): self
    {
        $element->setRequest($this->request);
        $element->setForm($this);
        // $element->setDefault();
        $element->prepare();
        $this->elements[$element->getName()] = $element;
        return $this;
    }

    public function removeElement(Element $element): self
    {
        if ($this->elementExists($element->getName())) {
            unset($this->elements[$element->getName()]);
        }
        return $this;
    }

    private function elementExists($name): bool
    {
        return isset($this->elements[$name]);
    }

    /**
     *
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    public function getFormCount()
    {
        return $this->cntForm;
    }

    /**
     * @param string $method
     * @return void
     */
    public function setMethod(?string $method = null): void
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
     * @param array $data
     * @return \self
     */
    public function setDefaults(array $data): self
    {

        if ($this->isSubmitted()) {
            $data = [];
            $method = $this->request->getMethod();
            foreach ($this->request->$method() as $key => $items) {
                if (in_array($key, [self::_TOKEN_CSRF_, self::_TOKEN_SUBMIT_])) {
                    continue;
                }
                $data[$key] = $items;
            }
        }
        $this->defaultsHandler = new DefaultsHandler($data);
        return $this;
    }

    /**
     * @return DefaultsHandler
     */
    public function getDefaultsHandler(): DefaultsHandler
    {
        return $this->defaultsHandler ?? new DefaultsHandler([]);
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
    protected function setName(?string $name = null): self
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
    protected function setAction(?string $action = null): self
    {
        $this->action = $action;
        $this->setAttribute('action', $this->getAction());

        if (is_null($action)) {
            $this->removeAttribute('action');
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSubmitted($validate = true): bool
    {
//        return $this->formSubmitted;
        if (!$this->formSubmitted) {
            return false;
        }
        //  dump($this->getElements());
        if ($validate !== false) {
            return Validator::check($this->getElements());
        }

        return true;
    }


}
