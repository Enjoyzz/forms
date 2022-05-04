<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Closure;
use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Interfaces\DefaultsHandlerInterface;
use Enjoys\Forms\Traits;
use Enjoys\ServerRequestWrapperInterface;
use Enjoys\Session\Session;
use Enjoys\Traits\Options;
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
    use Traits\Request {
        setRequest as private;
    }


    private const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];

    public const _TOKEN_CSRF_ = '_token_csrf';
    public const _TOKEN_SUBMIT_ = '_token_submit';

    //public const _FLAG_FORMMETHOD_ = '_form_method';
    public const ATTRIBUTES_DESC = '_desc_attributes_';
    public const ATTRIBUTES_VALIDATE = '_validate_attributes_';
    public const ATTRIBUTES_LABEL = '_label_attributes_';
    public const ATTRIBUTES_FIELDSET = '_fieldset_attributes_';
    public const ATTRIBUTES_FILLABLE_BASE = '_fillable_base_attributes_';


    private string $method = 'POST';
    private ?string $action = null;

    private DefaultsHandlerInterface $defaultsHandler;

    private bool $submitted = false;
    private Session $session;

    public function __construct(
        string $method = 'POST',
        string $action = null,
        ServerRequestWrapperInterface $request = null,
        DefaultsHandlerInterface $defaultsHandler = null,
        Session $session = null
    ) {
        $this->setRequest($request);
        $this->session = $session ?? new Session();
        $this->defaultsHandler = $defaultsHandler ?? new DefaultsHandler();

        $this->setMethod($method);
        $this->setAction($action);

        if ($this->submitted === true) {
            $this->setDefaults([]);
        }

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


    private function setSubmitted(bool $submitted): Form
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



//    public function __destruct()
//    {
//        static::$formCounter = 0;
//    }
//
//    public function getFormCounter(): int
//    {
//        return static::$formCounter;
//    }


    /**
     * @param array|Closure():array $data
     * @return $this
     * @noinspection PhpMissingParamTypeInspection
     */
    public function setDefaults($data): self
    {
        if ($data instanceof Closure) {
            $data = $data();
        }

        Assert::isArray($data);

        if ($this->submitted === true) {
            $data = [];

            $requestData = match (strtolower($this->getMethod())) {
                'get' => $this->getRequest()->getQueryData()->toArray(),
                'post' => $this->getRequest()->getPostData()->toArray(),
                default => [],
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
     *
     * Если prepare() ничего не возвращает (NULL), то элемент добавляется,
     * если что-то вернула функция, то элемент добавлен в коллекцию не будет.
     * @use Element::setForm()
     * @use Element::prepare()
     * @param Element $element
     * @return $this
     */
    public function addElement(Element $element): self
    {
        $element->setForm($this);
        return $this->parentAddElement($element);
    }

//    /**
//     * Вывод формы в Renderer
//     * @param RendererInterface $renderer
//     * @return mixed Возвращается любой формат, в зависимоти от renderer`а, может
//     * вернутся строка в html, или, например, xml или массив, все зависит от рендерера.
//     */
//    public function render(Renderer\RendererInterface $renderer)
//    {
//        $renderer->setForm($this);
//        return $renderer->render();
//    }

    public function getDefaultsHandler(): DefaultsHandlerInterface
    {
        return $this->defaultsHandler;
    }

    /**
     * @throws Exception\ExceptionRule
     */
    public function setMethod(string $method): void
    {
        if (in_array(strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = strtoupper($method);
        }
        $this->setAttribute(AttributeFactory::create('method', $this->method));
        $this->setOption('method', $this->method, false);
        $this->addElement(new Csrf($this->session));
        $this->setTokenSubmitElement();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setAction(?string $action = null): self
    {
        $this->action = $action;
        $this->setAttribute(AttributeFactory::create('action', $this->action));
        $this->setOption('action', $this->action, false);
        $this->setTokenSubmitElement();
        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }


    private function setTokenSubmitElement(): void
    {
        $tokenSubmit = new TockenSubmit(md5(json_encode($this->getOptions())));
        $this->addElement($tokenSubmit);
        $this->setSubmitted($tokenSubmit->getSubmitted());
    }
}
