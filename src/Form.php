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
class Form
{
    use Traits\Attributes;
    use Traits\Request;
    use Options;
    use Traits\Container {
        addElement as private parentAddElement;
    }

    /**
     * Разрешенные методы формы
     */
    private const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];

    /**
     * Название переменной для хранения токена для проверки от аттак CSRF
     */
    public const _TOKEN_CSRF_ = '_token_csrf';

    /**
     * Название переменной для хранения токена для проверки отправлена форма или нет
     */
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
     * @var DefaultsHandler
     */
    private DefaultsHandler $defaultsHandler;

    /**
     *
     * @var bool По умолчанию форма не отправлена
     */
    private bool $formSubmitted = false;

    /**
     * @static int Глобальный счетчик форм на странице
     * @readonly
     * @psalm-allow-private-mutation
     */
    private static int $formCounter = 0;

    /**
     * @example example/initform.php description
     * @param array $options
     * @param RequestInterface $request
     */
    public function __construct(array $options = [], RequestInterface $request = null)
    {
        $this->setRequest($request);
        static::$formCounter++;



        $tockenSubmit = $this->tockenSubmit(md5(\json_encode($options) . $this->getFormCounter()));
        $this->formSubmitted = $tockenSubmit->getSubmitted();

        if (!isset($this->defaultsHandler) && $this->formSubmitted === true) {
            $this->setDefaults([]);
        }

        $this->setOptions($options);
    }

    public function __destruct()
    {
        static::$formCounter = 0;
    }

    public function getFormCounter(): int
    {
        return static::$formCounter;
    }

    /**
     * Устанавливает метод формы, заодно пытается установить защиту от CSRF аттак,
     * если она требуется
     * @param string|null $method
     * @return void
     */
    public function setMethod(?string $method = null): void
    {
        if (is_null($method)) {
            $this->removeAttribute('method');
            return;
        }
        if (in_array(\strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = \strtoupper($method);
        }
        $this->setAttribute('method', $this->method);

        $this->csrf();
    }

    /**
     * Получение метода формы
     * @return string GET|POST
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Получение аттрибута action формы
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Установка аттрибута action формы
     * @param string|null $action
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
     * Set \Enjoys\Forms\FormDefaults $formDefaults
     * @param array $data
     * @return $this
     */
    public function setDefaults(array $data): self
    {

        if ($this->formSubmitted === true) {
            $data = [];
            $method = $this->getRequest()->getMethod();
            foreach ($this->getRequest()->$method() as $key => $items) {
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
     * Получение имени формы
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Установка аттрибута формы name
     * @param string|null $name
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
     * Возвращает true если форма отправлена и валидна.
     * На валидацию форма проверяется по умолчанию, усли использовать параметр $validate
     * false, проверка будет только на отправку формы
     * @param bool $validate
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

    /**
     *
     * Если prepare() ничего не возвращает (NULL), то элемент добавляется,
     * если что-то вернула фунция, то элемент добален в коллекцию не будет.
     * @use Element::setForm()
     * @use Element::prepare()
     * @param Element $element
     * @return $this
     */
    public function addElement(Element $element): self
    {
        $element->setForm($this);
        if ($element->prepare() !== null) {
            return $this;
        }
        return $this->parentAddElement($element);
    }

    /**
     * Вывод формы в Renderer
     * @param \Enjoys\Forms\Renderer\RendererInterface $renderer
     * @return mixed Возвращается любой формат, в зависимоти от renderer`а, может
     * вернутся строка в html, или, например, xml или массив, все зависит от рендерера.
     */
    public function render(Renderer\RendererInterface $renderer)
    {
        $renderer->setForm($this);
        return $renderer->render();
    }
}
