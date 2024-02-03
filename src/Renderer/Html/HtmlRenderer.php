<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Html;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Helper;
use Enjoys\Forms\Interfaces\RendererInterface;
use Enjoys\Forms\Renderer\Html\TypesRender\TypeRenderInterface;
use Enjoys\Forms\Renderer\Renderer;

class HtmlRenderer extends Renderer implements RendererInterface
{
    private const _MAP_ = [
        TypesRender\Button::class => [
            Elements\Button::class,
            Elements\Submit::class,
            Elements\Reset::class,
            Elements\Image::class
        ],
        TypesRender\Radio::class => [
            Elements\Radio::class,
            Elements\Checkbox::class
        ],
        TypesRender\Select::class => Elements\Select::class,
        TypesRender\Html::class => [
            Elements\Html::class,
            Elements\Header::class
        ]
    ];

    public static function createTypeRender(Element $element): TypeRenderInterface
    {
        /** @var false|string $typeRenderClass */
        $typeRenderClass = Helper::arrayRecursiveSearchKeyMap(get_class($element), self::_MAP_)[0] ?? false;
        if ($typeRenderClass === false || !class_exists($typeRenderClass)) {
            return new TypesRender\Input($element);
        }
        /** @var class-string<TypeRenderInterface> $typeRenderClass */
        return new $typeRenderClass($element);
    }

    public function rendererHiddenElements(): string
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

    public function rendererElements(): string
    {
        $html = [];
        foreach ($this->getForm()->getElements() as $element) {
            $html[] = "<div>" . self::createTypeRender($element)->render() . "</div>";
        }
        return implode("\n", $html);
    }

    public function rendererElement(string $elementName): string
    {
        $element = $this->getForm()->getElement($elementName);
        if ($element === null) {
            return '';
        }
        return "<div>" . self::createTypeRender($element)->render() . "</div>";
    }

    public function output(): string
    {
        return sprintf(
            "<form%s>\n%s\n%s\n</form>",
            $this->getForm()->getAttributesString(),
            $this->rendererHiddenElements(),
            $this->rendererElements()
        );
    }
}
