<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Renderer\RendererInterface;
use Enjoys\Forms\Traits;
use Enjoys\Http\ServerRequestInterface;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Options;

use HttpSoft\ServerRequest\ServerRequestCreator;

use function json_encode;
use function strtoupper;

/**
 * Class Form
 * @package Enjoys\Forms
 */
class Form
{
    use Traits\Attributes;
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
    //public const _FLAG_FORMMETHOD_ = '_form_method';
    public const ATTRIBUTES_DESC = '_desc_attributes_';
    public const ATTRIBUTES_VALIDATE = '_validate_attributes_';
    public const ATTRIBUTES_LABEL = '_label_attributes_';
    public const ATTRIBUTES_FIELDSET = '_fieldset_attributes_';
    public const ATTRIBUTES_FILLABLE_BASE = '_fillable_base_attributes_';

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     *
     * @var string POST|GET
     */
    private string $method = 'GET';

    /**
     *
     * @var string|null
     */
    private ?string $action = null;

    /**
     *
     * @var DefaultsHandlerInterface
     */
    private DefaultsHandlerInterface $defaultsHandler;

    /**
     *
     * @var bool По умолчанию форма не отправлена
     */
    private bool $formSubmitted;

    /**
     * @static int Глобальный счетчик форм на странице
     * @readonly
     * @psalm-allow-private-mutation
     */
    private static int $formCounter = 0;
    private ServerRequestWrapper $requestWrapper;

    /**
     * @example example/initform.php description
     */
    public function __construct(
        array $options = [],
        ServerRequestWrapper $requestWrapper = null,
        DefaultsHandlerInterface $defaults = null
    ) {
        $this->defaultsHandler = $defaults ?? new DefaultsHandler();
        $this->setRequestWrapper($requestWrapper);

        static::$formCounter++;


        $this->setOptions($options);

        $tokenSubmit = $this->tockenSubmit(
            md5(
                json_encode($options)
                . ($this->getOption('inclCounter', false) ? $this->getFormCounter() : '')
            )
        );
        $this->formSubmitted = $tokenSubmit->getSubmitted();

        if ($this->formSubmitted === true) {
            $this->setDefaults([]);
        }
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
        if (in_array(strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = strtoupper($method);
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
     * Set \Enjoys\Forms\DefaultsHandlerInterface $defaultsHandler
     * @param \Closure|array $data
     * @return $this
     */
    public function setDefaults($data): self
    {
        if ($data instanceof \Closure) {
            $data = $data();
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid argument, expected array or closure with retun array.');
        }

        if ($this->formSubmitted === true) {
            $data = [];
            $method = $this->getRequestWrapper()->getRequest()->getMethod();
            $requestData = match(strtolower($method)){
                'get' => $this->getRequestWrapper()->getQueryData()->getAll(),
                'post' => $this->getRequestWrapper()->getPostData()->getAll(),
                default => []
            };
            foreach ($requestData as $key => $items) {
                if (in_array($key, [self::_TOKEN_CSRF_, self::_TOKEN_SUBMIT_])) {
                    continue;
                }
                $data[$key] = $items;
            }
        }
        $this->defaultsHandler->setData($data);
        return $this;
    }

    /**
     * @return DefaultsHandlerInterface
     */
    public function getDefaultsHandler(): DefaultsHandlerInterface
    {
        return $this->defaultsHandler;
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
     * @param RendererInterface $renderer
     * @return mixed Возвращается любой формат, в зависимоти от renderer`а, может
     * вернутся строка в html, или, например, xml или массив, все зависит от рендерера.
     */
    public function render(Renderer\RendererInterface $renderer)
    {
        $renderer->setForm($this);
        return $renderer->render();
    }

    private function setRequestWrapper(ServerRequestWrapper|null $requestWrapper)
    {
        $this->requestWrapper = $requestWrapper ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
    }

    public function getRequestWrapper(): ServerRequestWrapper
    {
        return $this->requestWrapper;
    }


}


