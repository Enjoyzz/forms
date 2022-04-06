<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Closure;
use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Renderer\RendererInterface;
use Enjoys\Forms\Traits;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Options;
use HttpSoft\ServerRequest\ServerRequestCreator;
use Webmozart\Assert\Assert;

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


    private string $method = 'POST';
    private ?string $action = null;
    private ServerRequestWrapper $request;
    private DefaultsHandlerInterface $defaultsHandler;

    private bool $submitted = false;

    public function __construct(
        string $method = 'POST',
        string $action = null,
        DefaultsHandlerInterface $defaultsHandler = null
    ) {
        $this->request = new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
        $this->setMethod($method);
        $this->setAction($action);
        $this->defaultsHandler = $defaultsHandler ?? new DefaultsHandler();


        $this->addElement(new TockenSubmit(md5(json_encode($this->getOptions()))));
        $this->setSubmitted();

//
//        static::$formCounter++;
//
//
//        $this->setOptions($options);
//
//        $tokenSubmit = $this->tockenSubmit(
//            md5(
//                json_encode($options)
//                . ($this->getOption('inclCounter', false) ? $this->getFormCounter() : '')
//            )
//        );
//        $this->formSubmitted = $tokenSubmit->getSubmitted();
//
//        if ($this->formSubmitted === true) {
//            $this->setDefaults([]);
//        }
    }

    public function setSubmitted(bool $submitted = false): Form
    {
        $this->submitted = $submitted;
        return $this;
    }

    /**
     * Возвращает true если форма отправлена и валидна.
     * На валидацию форма проверяется по умолчанию, если использовать параметр $validate
     * false, проверка будет только на отправку формы
     * @param bool $validate
     * @return bool
     */
    public function isSubmitted(bool $validate = true): bool
    {
        if ($this->submitted === false) {
            return false;
        }
        //  dump($this->getElements());
        if ($validate !== false) {
            return Validator::check($this->getElements());
        }

        return true;
    }

    public function setRequest(ServerRequestWrapper $request = null): Form
    {
        $this->request = $request ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
        return $this;
    }

    public function getRequest(): ServerRequestWrapper
    {
        return $this->request;
    }

    public function getDefaultsHandler(): DefaultsHandlerInterface
    {
        return $this->defaultsHandler;
    }

//    public function __destruct()
//    {
//        static::$formCounter = 0;
//    }
//
//    public function getFormCounter(): int
//    {
//        return static::$formCounter;
//    }

    public function setMethod(string $method): void
    {
        if (in_array(strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = strtoupper($method);
        }
        $this->setAttr(AttributeFactory::create('method', $this->method));
        $this->setOption('method', $method, false);
//        $this->csrf();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setAction(?string $action = null): self
    {
        $this->action = $action;
        $this->setAttr(AttributeFactory::create('action', $this->action));
        $this->setOption('action', $this->action, false);
        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set \Enjoys\Forms\DefaultsHandlerInterface $defaultsHandler
     * @param array|Closure():array $data
     * @return $this
     */
    public function setDefaults($data): self
    {
        if ($data instanceof Closure) {
            $data = $data();
        }

        Assert::isArray($data);

        if ($this->submitted === true) {
            $data = [];
            $requestData = match ($this->getMethod()) {
                'get' => $this->getRequest()->getQueryData()->getAll(),
                'post' => $this->getRequest()->getPostData()->getAll(),
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
        $this->setAttr(AttributeFactory::create('name', $this->name));

        if (is_null($name)) {
            $this->getAttributeCollection()->remove('name');
        }

        return $this;
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


}


