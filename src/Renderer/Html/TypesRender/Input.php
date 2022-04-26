<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\TypeRenderInterface;

class Input implements TypeRenderInterface
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
    }

    protected function bodyRender(Element $element): string
    {
        return $element->baseHtml();
    }


    protected function descriptionRender(): string
    {
        $element = $this->getElement();
        if (!($element instanceof Descriptionable) || empty($element->getDescription())) {
            return '';
        }
        /** @var Element&Descriptionable $element */
        return sprintf(
            '<small%s>%s</small>',
            $element->getAttributesString(Form::ATTRIBUTES_DESC),
            $element->getDescription()
        );
    }


    protected function validationRender(): string
    {
        if (!method_exists($this->getElement(), 'isRuleError') || !$this->getElement()->isRuleError()) {
            return '';
        }
        return sprintf(
            '<div%s>%s</div>',
            $this->getElement()->getAttributesString(Form::ATTRIBUTES_VALIDATE),
            $this->getElement()->getRuleErrorMessage()
        );
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

        return sprintf(
            '<label%s>%s%s</label>',
            $this->getElement()->getAttributesString(Form::ATTRIBUTES_LABEL),
            $this->getElement()->getLabel(),
            $star
        );
    }
}
