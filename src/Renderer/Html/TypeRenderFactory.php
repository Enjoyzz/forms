<?php

declare(strict_types=1);


namespace Enjoys\Forms\Renderer\Html;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Helper;
use Enjoys\Forms\Renderer\Html\TypesRender;
use Enjoys\Forms\Renderer\TypesRenderInterface;

final class TypeRenderFactory
{

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

    public static function create(Element $element): TypesRenderInterface
    {
        $typeRenderClass = Helper::arrayRecursiveSearchKeyMap(get_class($element), self::_MAP_)[0] ?? false;
        if ($typeRenderClass === false || !class_exists($typeRenderClass)) {
            return new TypesRender\Input($element);
        }
        return new $typeRenderClass($element);
    }
}