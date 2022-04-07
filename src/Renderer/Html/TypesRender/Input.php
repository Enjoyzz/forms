<?php

declare(strict_types=1);


namespace Enjoys\Forms\Renderer\Html\TypesRender;


use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\TypesRenderInterface;

class Input implements TypesRenderInterface
{

    public function __construct(private Element $element)
    {
    }

    public function getElement(): Element
    {
        return $this->element;
    }


    public function render(): string
    {
        return sprintf(
            "%s\n%s\n%s\n%s",
            $this->labelRender(),
            $this->bodyRender($this->element),
            $this->descriptionRender(),
            $this->validationRender(),
        );
//            $this->renderLabel($this->element) .
//            $this->renderBody($this->element) .
//            $this->renderDescription($this->element) .
//            $this->renderValidation($this->element) .
//            '';
    }

    protected function bodyRender(Element $element): string
    {
        return  $element->baseHtml();
    }


    protected function descriptionRender(): string
    {
        if (!method_exists($this->getElement(), 'getDescription') || empty($this->getElement()->getDescription())) {
            return '';
        }
        return "<small{$this->getElement()->getAttributesString(Form::ATTRIBUTES_DESC)}>{$this->getElement()->getDescription()}</small>";
    }


    protected function validationRender(): string
    {
        if (!method_exists($this->getElement(), 'isRuleError') || !$this->getElement()->isRuleError()) {
            return '';
        }
        return "<div{$this->getElement()->getAttributesString(Form::ATTRIBUTES_VALIDATE)}>{$this->getElement()->getRuleErrorMessage()}</div>";
    }


    protected function labelRender(string $star = "&nbsp;<sup>*</sup>"): string
    {
        if (empty($this->getElement()->getLabel())) {
            return '';
        }

        if (!method_exists($this->getElement(), 'isRequired') || !$this->getElement()->isRequired()) {
            $star = "";
        }

        if (null !== $idAttribute = $this->getElement()->getAttr('id')) {
            $this->getElement()->setAttr($idAttribute->withName('for'), Form::ATTRIBUTES_LABEL);
        }

        return "<label{$this->getElement()->getAttributesString(Form::ATTRIBUTES_LABEL)}>{$this->getElement()->getLabel()}{$star}</label>";
    }


}