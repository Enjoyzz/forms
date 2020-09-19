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

/**
 * 
 * Class Forms
 * 
 * 
 * @author Enjoys
 * 
 */
class Forms {

    use Traits\Attributes;

    const _ALLOWED_FORM_METHOD_ = ['GET', 'POST'];

    /**
     *
     * @var string|null   
     */
    private ?string $name;

    /**
     *
     * @var string POST|GET 
     */
    private $method = 'GET';

    /**
     *
     * @var string|null   
     */
    private ?string $action;

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
     * @param string $method
     * @param string $action
     */
    public function __construct(string $method = null, string $action = null) {


        if (!is_null($method)) {
            $this->setMethod($method);
        }

        if (!is_null($action)) {
            $this->setAction($action);
        }
    }

    /**
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     * @return $this
     */
    public function setName(?string $name): self {
        $this->name = $name;
        $this->setAttribute('name', $this->name);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getAction(): string {
        return $this->action;
    }

    /**
     *
     * @param string $action
     * @return $this
     */
    public function setAction(?string $action): self {
        $this->action = $action;
        $this->setAttribute('action', $this->action);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * 
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): self {
        if (in_array(\strtoupper($method), self::_ALLOWED_FORM_METHOD_)) {
            $this->method = \strtoupper($method);
        }
        $this->setAttribute('method', $this->method);
        return $this;
    }

    /**
     * @method Elements\Text text(string $name, string $title)
     * @method Elements\Hidden hidden(string $name, string $value)
     * @method Elements\Password password(string $name, string $title)
     * @method Elements\Submit submit(string $name, string $title)
     *  
     * @mixin Element
     */
    public function __call(string $name, array $arguments): Element {
        $class_name = '\Enjoys\\Forms\Elements\\' . ucfirst($name);

        /** @var Element $element */
        $element = new $class_name(...$arguments);
        $this->elements[] = $element;
        return $element;
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
        try {
            if (!class_exists($renderer)) {
                throw new Exception("Class <b>{$renderer}</b> not found");
            }
            return new $renderer($this);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
