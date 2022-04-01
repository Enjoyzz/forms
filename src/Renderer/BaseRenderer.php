<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Form;


class BaseRenderer implements RendererInterface
{

    protected Form $form;
    protected array $elements = [];

    public function __construct(Form $form = null)
    {
        $this->form = $this->setForm($form ?? new Form());
    }
    /**
     *
     * @param Form $form
     * @return Form
     */
    public function setForm(Form $form): Form
    {
        $this->form = $form;
        $this->elements = $this->form->getElements();
        return $this->form;
    }

    /**
     *
     * @return string
     */
    protected function hiddenRender(): string
    {
        $html = '';
        /** @var Element $element */
        foreach ($this->elements as $key => $element) {
            if ($element instanceof Hidden) {
                unset($this->elements[$key]);
                $html .= $element->baseHtml();
            }
            continue;
        }
        return $html;
    }

    /**
     *
     * @param array|null $elements
     * @psalm-param null|array{Element} $elements
     * @return string
     */
    protected function elementsRender(?array $elements = null): string
    {
        $elements ??= $this->elements;
        $html = '';

        foreach ($elements as $key => $element) {
            unset($elements[$key]);

            if (!($element instanceof Element)) {
                continue;
            }

            $html .= $this->elementRender($element);
        }
        return $html;
    }

    /**
     *
     * @param Element $element
     * @return string
     */
    public function elementRender(Element $element): string
    {
        $elementRender = new BaseElementRender($element);
        return $elementRender->render();
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        return "<form{$this->form->getAttributesString()}>"
                . $this->hiddenRender()
                . $this->elementsRender($this->elements)
                . "</form>";
    }
}
