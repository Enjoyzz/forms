<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Html\TypesRender;

use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
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
            $element->getDescription() ?? ''
        );
    }


    protected function validationRender(): string
    {
        $element = $this->getElement();
        if (!($element instanceof Ruleable) || !$element->isRuleError()) {
            return '';
        }
        return sprintf(
            '<div%s>%s</div>',
            $element->getAttributesString(Form::ATTRIBUTES_VALIDATE),
            $element->getRuleErrorMessage() ?? ''
        );
    }


    protected function labelRender(string $star = "&nbsp;<sup>*</sup>"): string
    {
        $element = $this->getElement();

        if (empty($element->getLabel())) {
            return '';
        }

        if (!method_exists($element, 'isRequired') || !$element->isRequired()) {
            $star = "";
        }

        if (null !== $idAttribute = $element->getAttribute('id')) {
            $element->setAttribute($idAttribute->withName('for'), Form::ATTRIBUTES_LABEL);
        }

        return sprintf(
            '<label%s>%s%s</label>',
            $element->getAttributesString(Form::ATTRIBUTES_LABEL),
            $element->getLabel() ?? '',
            $star
        );
    }
}
