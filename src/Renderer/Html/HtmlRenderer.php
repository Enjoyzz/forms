<?php

declare(strict_types=1);


namespace Enjoys\Forms\Renderer\Html;


use Enjoys\Forms\Element;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Helper;
use Enjoys\Forms\Renderer\RendererInterface;
use Enjoys\Forms\Renderer\RendererTrait;
use Enjoys\Forms\Renderer\TypesRenderInterface;

class HtmlRenderer implements RendererInterface
{

    use RendererTrait;

    private const _MAP_ = [
        TypesRender\Button::class => [
            Elements\Button::class,
            Elements\Submit::class,
            Elements\Reset::class,
            Elements\Image::class
        ],
        TypesRender\Radio::class => Elements\Radio::class,
        TypesRender\Checkbox::class => Elements\Checkbox::class,
        TypesRender\Select::class => Elements\Select::class,
        TypesRender\Html::class => [
            Elements\Html::class,
            Elements\Header::class
        ],
        TypesRender\Textarea::class => Elements\Textarea::class
    ];

    public static function createTypeRender(Element $element): TypesRenderInterface
    {
        $typeRenderClass = Helper::arrayRecursiveSearchKeyMap(get_class($element), self::_MAP_)[0] ?? false;
        if ($typeRenderClass === false || !class_exists($typeRenderClass)) {
            return new TypesRender\Input($element);
        }
        return new $typeRenderClass($element);
    }

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
            $html[] = "<div>".self::createTypeRender($element)->render()."</div>";
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