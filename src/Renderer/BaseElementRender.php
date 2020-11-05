<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Enjoys\Forms\Renderer;

use Enjoys\Forms\ElementInterface;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Renderer\ElementsRender;

/**
 * Description of ElementRender
 *
 * @author Enjoys
 */
class BaseElementRender
{

    protected $elementRender;
    protected $renderer;

    private const MAPCLASSES = [
        Elements\Radio::class => ElementsRender\RadioRender::class,
        Elements\Checkbox::class => ElementsRender\CheckboxRender::class,
        Elements\Select::class => ElementsRender\SelectRender::class,
        Elements\Header::class => ElementsRender\HeaderRender::class,
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

    public function __construct(ElementInterface $element)
    {
        $this->elementRender = $this->getElementRender($element);
    }

    /**
     *
     * @param type $element
     * @return ElementRenderInterface
     */
    protected function getElementRender(ElementInterface $element): ElementsRender\ElementRenderInterface
    {
        $key = \get_class($element);
        if (array_key_exists($key, self::MAPCLASSES)) {
            $class = self::MAPCLASSES[$key];

            return new $class($element);
        }

        return new ElementsRender\InputRender($element);
    }

    public function render()
    {
        return $this->elementRender->render() . "<br />\n";
    }
}
