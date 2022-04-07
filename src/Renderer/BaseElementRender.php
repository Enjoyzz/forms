<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Renderer\ElementsRender;


class BaseElementRender
{

    protected ElementsRender\ElementRenderInterface $elementRender;

    private const MAPCLASSES = [
        Elements\Radio::class => ElementsRender\RadioRender::class,
        Elements\Checkbox::class => ElementsRender\CheckboxRender::class,
        Elements\Select::class => ElementsRender\SelectRender::class,
        Elements\Header::class => ElementsRender\HeaderRender::class,
        Elements\Html::class => ElementsRender\HtmlRender::class,
        Elements\Button::class => ElementsRender\ButtonRender::class,
        Elements\Submit::class => ElementsRender\ButtonRender::class,
        Elements\Reset::class => ElementsRender\ButtonRender::class,
        Elements\Image::class => ElementsRender\ButtonRender::class,
        Elements\File::class => ElementsRender\InputRender::class,
        Elements\Text::class => ElementsRender\InputRender::class,
        //Elements\Color::class => ElementsRender\InputRender::class,
        Elements\Date::class => ElementsRender\InputRender::class,
        Elements\Datetime::class => ElementsRender\InputRender::class,
        Elements\Datetimelocal::class => ElementsRender\InputRender::class,
        Elements\Email::class => ElementsRender\InputRender::class,
        Elements\Month::class => ElementsRender\InputRender::class,
        Elements\Number::class => ElementsRender\InputRender::class,
        Elements\Password::class => ElementsRender\InputRender::class,
        Elements\Range::class => ElementsRender\InputRender::class,
        Elements\Tel::class => ElementsRender\InputRender::class,
        Elements\Time::class => ElementsRender\InputRender::class,
        Elements\Url::class => ElementsRender\InputRender::class,
        Elements\Week::class => ElementsRender\InputRender::class,
        Elements\Search::class => ElementsRender\InputRender::class,
        Elements\Textarea::class => ElementsRender\TextareaRender::class,
        Elements\Group::class => ElementsRender\GroupRender::class,
        Elements\Datalist::class => ElementsRender\DatalistRender::class,
    ];

    public function __construct(Element $element)
    {
        $this->elementRender = $this->getElementRender($element);
    }

    /**
     *
     * @param Element $element
     * @return ElementsRender\ElementRenderInterface
     */
    protected function getElementRender(Element $element): ElementsRender\ElementRenderInterface
    {
        $key = \get_class($element);
        if (array_key_exists($key, self::MAPCLASSES)) {
            $class = self::MAPCLASSES[$key];

            return new $class($element);
        }

        return new ElementsRender\InputRender($element);
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->elementRender->render() . "<br />\n";
    }
}
