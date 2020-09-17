<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace xForms;

/**
 * @author Enjoys
 * 
 */
class xForms {

    private $form_name = '';
    private $form_method = 'POST';
    private $allowed_form_method = ['GET', 'POST'];
    private $form_action = '';
    private $elements = [];

    /**
     * 
     * @param string $name
     * @param string $method
     * @param string $action
     */
    public function __construct() {
        $this->setFormName(uniqid('form'));
        $this->setFormAction($_SERVER['REQUEST_URI']);
    }

    /**
     * 
     * @return string
     */
    public function getFormName(): string {
        return $this->form_name;
    }

    /**
     * 
     * @param string $name
     * @return $this
     */
    public function setFormName(string $name) {
        $this->form_name = $name;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getFormAction(): string {
        return $this->form_action;
    }

    /**
     *
     * @param string $action
     * @return $this
     */
    public function setFormAction(string $action) {
        $this->form_action = $action;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getFormMethod(): string {
        return $this->form_method;
    }

    /**
     * 
     * @param string $method
     * @return $this
     */
    public function setFormMethod(string $method) {
        if (in_array(\strtoupper($method), $this->allowed_form_method)) {
            $this->form_method = \strtoupper($method);
        }
        return $this;
    }

    /**
     * @method Elements\Text text(string $name, string $title)
     * @method Elements\Password password(string $name, string $title)
     *  
     * @return \xForms\Element
     */
    public function __call(string $name, array $arguments) {
        $class_name = '\xForms\Elements\\' . ucfirst($name);

        /** @var \xForms\Element $element */
        $element = new $class_name(...$arguments);
        $this->elements[$element->getName()] = $element;
        return $element;
    }

    /**

     * @param \xForms\Element $element
     * @return \xForms\Element
     */
    private function setElement(Element $element): Element {
        $this->elements[] = $element;
        return $element;
    }

}
