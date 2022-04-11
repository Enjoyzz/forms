<?php

declare(strict_types=1);


namespace Enjoys\Forms\Renderer\Html;


use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Renderer\RendererInterface;
use Enjoys\Forms\Renderer\RendererTrait;

class HtmlRenderer implements RendererInterface
{

    use RendererTrait;

    private function rendererHiddenElements(): string
    {
        $html = [];
        foreach ($this->getForm()->getElements() as $element) {
            if ($element instanceof Hidden) {
                $this->getForm()->removeElement($element);
                $html[] = $element->baseHtml();
            }
        }
        return implode("\n", $html);
    }

    private function rendererElements(): string
    {
        $html = [];
        foreach ($this->getForm()->getElements() as $element) {
            $html[] = "<div>".TypeRenderFactory::create($element)->render()."</div>";
        }
        return implode("\n", $html);
    }

    public function output(): string
    {
        return sprintf(
            "<form%s>\n%s\n%s\n</form>",
            $this->form->getAttributesString(),
            $this->rendererHiddenElements(),
            $this->rendererElements()
        );
    }
}