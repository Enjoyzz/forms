<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Closure;
use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Interfaces\DefaultsHandlerInterface;
use Enjoys\Forms\Traits;
use Enjoys\Forms\Traits\Options;
use Enjoys\Session\Session;
use HttpSoft\ServerRequest\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

use function strtoupper;

class Form
{
    use Traits\Attributes;
    use Options;
    use Traits\Container {
        addElement as private parentAddElement;
    }

    private const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];

    public const _TOKEN_CSRF_ = '_token_csrf';
    public const _TOKEN_SUBMIT_ = '_token_submit';

    public const ATTRIBUTES_DESC = '_desc_attributes_';
    public const ATTRIBUTES_VALIDATE = '_validate_attributes_';
    public const ATTRIBUTES_LABEL = '_label_attributes_';
    public const ATTRIBUTES_FIELDSET = '_fieldset_attributes_';
    public const ATTRIBUTES_FILLABLE_BASE = '_fillable_base_attributes_';


    private string $method = 'POST';
    private ?string $action = null;
    private ?string $id = null;

    private ServerRequestInterface $request;
    private DefaultsHandlerInterface $defaultsHandler;

    private bool $submitted = false;
    private Session $session;

    /**
     * @throws Exception\ExceptionRule
     */
    public function __construct(
        string $method = 'POST',
        ?string $action = null,
        ?string $id = null,
        ?ServerRequestInterface $request = null,
        ?DefaultsHandlerInterface $defaultsHandler = null,
        ?Session $session = null
    ) {
        $this->request = $request ?? ServerRequestCreator::createFromGlobals();
        $this->session = $session ?? new Session();
        $this->defaultsHandler = $defaultsHandler ?? new DefaultsHandler();

        $this->setMethod($method);
        $this->setAction($action);
        $this->setId($id);
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
        if ($validate !== false) {
            return $this->validate();
        }
        return true;
    }

    public function validate(): bool
    {
        return Validator::check($this->getElements());
    }


    /**
     * @param array|Closure():array $data
     * @return $this
     */
    public function setDefaults(array|Closure $data): Form
    {
        if ($this->submitted === true) {
            /** @var array $requestData */
            $requestData = match ($this->getMethod()) {
                'GET' => $this->getRequest()->getQueryParams(),
                'POST' => $this->getRequest()->getParsedBody(),
                default => [],
            };
            $data = array_filter(
                $requestData,
                function ($k) {
                    return !in_array($k, [self::_TOKEN_CSRF_, self::_TOKEN_SUBMIT_]);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        if ($data instanceof Closure) {
            $data = $data();
            /** @psalm-suppress RedundantConditionGivenDocblockType */
            Assert::isArray($data);
        }

        $this->defaultsHandler->setData($data);
        return $this;
    }


    /**
     *
     * Если prepare() возвращает false, то элемент добавляется,
     * если true, то элемент добавлен в коллекцию не будет.
     * @use Element::setForm()
     * @use Element::prepare()
     * @param Element $element
     * @param string|null $before
     * @param string|null $after
     * @return $this
     */
    public function addElement(Element $element, ?string $before = null, ?string $after = null): static
    {
        $element->setForm($this);
        return $this->parentAddElement($element, $before, $after);
    }


    public function getDefaultsHandler(): DefaultsHandlerInterface
    {
        return $this->defaultsHandler;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
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

    public function setAction(?string $action): self
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

    public function setId(?string $id): Form
    {
        $this->id = $id;
        $this->setAttribute(AttributeFactory::create('id', $this->id));
        $this->setOption('id', $this->id, false);
        $this->setTokenSubmitElement();
        return $this;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    private function setTokenSubmitElement(): void
    {
        $tokenSubmit = new TokenSubmit($this);
        $this->addElement($tokenSubmit->getElement());

        $this->submitted = $tokenSubmit->validate();

        // after every update the token submit, form needs check
        // and update defaults if form has been submitted.
        // Maybe in future will refactor this code
        if ($this->submitted === true) {
            $this->setDefaults([]);
        }
    }

//    /**
//     * Вывод формы в Renderer
//     * @param \Enjoys\Forms\Interfaces\RendererInterface $renderer
//     * @return mixed Возвращается любой формат, в зависимоти от renderer`а, может
//     * вернутся строка в html, или, например, xml или массив, все зависит от рендерера.
//     */
//    public function render(\Enjoys\Forms\Interfaces\RendererInterface $renderer): mixed
//    {
//        $renderer->setForm($this);
//        return $renderer->output();
//    }
}
