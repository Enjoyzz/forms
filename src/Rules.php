<?php

declare(strict_types=1);

namespace Enjoys\Forms;


use Enjoys\Forms\Rule;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;

/**
 * @author Enjoys
 */
class Rules
{


    public const CALLBACK = Rule\Callback::class;
    public const CAPTCHA = Rule\Captcha::class;
    public const EMAIL = Rule\Email::class;
    public const EQUAL = Rule\Equal::class;
    public const LENGTH = Rule\Length::class;
    public const REGEXP = Rule\Regexp::class;
    public const REQUIRED = Rule\Required::class;
    public const UPLOAD = Rule\Upload::class;

    /**
     *
     * @var string|null
     */
    private ?string $message;

    /**
     *
     * @var array
     */
    private array $params = [];
    private ServerRequestWrapper $request;

    /**
     *
     * @param string|null $message
     * @param mixed $params
     */
    public function __construct(?string $message = null, $params = [])
    {
        $this->setParams($params);
        $this->message = $this->setMessage($message);
        $this->setRequest();
    }

    /**
     *
     * @param mixed $params
     * @return void
     */
    public function setParams($params): void
    {

        if (is_array($params)) {
            $this->params = $params;
            return;
        }
        $this->params[] = $params;
    }

    /**
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     *
     * @param string|int $key
     * @return mixed|null
     */
    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    /**
     *
     * @param string|null $message
     * @return string|null
     */
    public function setMessage(?string $message = null): ?string
    {
        return $this->message = $message;
    }

    /**
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {

        return $this->message;
    }

    public function setRequest(ServerRequestWrapper $request = null)
    {
        $this->request = $request ?? new ServerRequestWrapper(ServerRequestCreator::createFromGlobals());
    }

    public function getRequest(): ServerRequestWrapper
    {
        return $this->request;
    }
}
