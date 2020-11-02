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

namespace Enjoys\Forms\Renderer\Bootstrap4;

use Enjoys\Forms\ElementInterface;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Renderer\BaseElementRender;
use Enjoys\Forms\Renderer\ElementsRender\ButtonRender;
use Enjoys\Forms\Renderer\ElementsRender\ElementRenderInterface;

/**
 * Description of ElementRender
 *
 * @author Enjoys
 */
class Bootstrap4ElementRender extends BaseElementRender
{

    private const mapClasses = [
        Elements\Radio::class => Bootstrap4RadioRender::class,
        Elements\Checkbox::class => Bootstrap4CheckboxRender::class,
        Elements\Select::class => Bootstrap4SelectRender::class,
        Elements\Header::class => Bootstrap4HeaderRender::class,
        Elements\File::class => Bootstrap4FileRender::class,
        Elements\Button::class => ButtonRender::class,
        Elements\Submit::class => ButtonRender::class,
        Elements\Reset::class => ButtonRender::class,
        Elements\Image::class => ButtonRender::class,
        Elements\Text::class => Bootstrap4InputRender::class,
        Elements\Color::class => Bootstrap4InputRender::class,
        Elements\Date::class => Bootstrap4InputRender::class,
        Elements\Datetime::class => Bootstrap4InputRender::class,
        Elements\Datetimelocal::class => Bootstrap4InputRender::class,
        Elements\Email::class => Bootstrap4InputRender::class,
        Elements\Month::class => Bootstrap4InputRender::class,
        Elements\Number::class => Bootstrap4InputRender::class,
        Elements\Password::class => Bootstrap4InputRender::class,
        Elements\Range::class => Bootstrap4InputRender::class,
        Elements\Tel::class => Bootstrap4InputRender::class,
        Elements\Time::class => Bootstrap4InputRender::class,
        Elements\Url::class => Bootstrap4InputRender::class,
        Elements\Week::class => Bootstrap4InputRender::class,
        Elements\Search::class => Bootstrap4InputRender::class,
        Elements\Textarea::class => Bootstrap4InputRender::class,
        Elements\Datalist::class => Bootstrap4DatalistRender::class,
    ];

    public function render()
    {
        $html = '<div class="form-group">';
        $html .= $this->elementRender->render();
        $html .= '</div>';
        return $html;
    }

    /**
     * 
     * @param type $element
     * @return ElementRenderInterface
     */
    protected function getElementRender(ElementInterface $element): ElementRenderInterface
    {
        $key = \get_class($element);
        if (array_key_exists($key, self::mapClasses)) {
            $class = self::mapClasses[$key];
            return new $class($element);
        }

        return parent::getElementRender($element);
    }
}
