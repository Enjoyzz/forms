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

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Captcha\CaptchaInterface;
use Enjoys\Forms\Element;
use Enjoys\Forms\Elements\Button;
use Enjoys\Forms\Elements\Captcha;
use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Color;
use Enjoys\Forms\Elements\Csrf;
use Enjoys\Forms\Elements\Datalist;
use Enjoys\Forms\Elements\Date;
use Enjoys\Forms\Elements\Datetime;
use Enjoys\Forms\Elements\Datetimelocal;
use Enjoys\Forms\Elements\Email;
use Enjoys\Forms\Elements\File;
use Enjoys\Forms\Elements\Group;
use Enjoys\Forms\Elements\Header;
use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Elements\Html;
use Enjoys\Forms\Elements\Image;
use Enjoys\Forms\Elements\Month;
use Enjoys\Forms\Elements\Number;
use Enjoys\Forms\Elements\Password;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Elements\Range;
use Enjoys\Forms\Elements\Reset;
use Enjoys\Forms\Elements\Search;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Submit;
use Enjoys\Forms\Elements\Tel;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Elements\Textarea;
use Enjoys\Forms\Elements\Time;
use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Elements\Url;
use Enjoys\Forms\Elements\Week;
use Webmozart\Assert\Assert;

/**
 * @method Text text(string $name, string $label = null)
 * @method Hidden hidden(string $name, string $value = null)
 * @method Password password(string $name, string $label = null)
 * @method Submit submit(string $name, string $title = null)
 * @method Header header(string $title = null)
 * @method Color color(string $name, string $label = null)
 * @method Date date(string $name, string $label = null)
 * @method Datetime datetime(string $name, string $label = null)
 * @method Datetimelocal datetimelocal(string $name, string $label = null)
 * @method Email email(string $name, string $label = null)
 * @method Number number(string $name, string $label = null)
 * @method Range range(string $name, string $label = null)
 * @method Search search(string $name, string $label = null)
 * @method Tel tel(string $name, string $label = null)
 * @method Time time(string $name, string $label = null)
 * @method Url url(string $name, string $label = null)
 * @method Month month(string $name, string $label = null)
 * @method Week week(string $name, string $label = null)
 * @method Textarea textarea(string $name, string $label = null)
 * @method Select select(string $name, string $label = null)
 * @method Button button(string $name, string $title = null)
 * @method Datalist datalist(string $name, string $label = null)
 * @method Checkbox checkbox(string $name, string $label = null)
 * @method Image image(string $name, string $src = null)
 * @method Radio radio(string $name, string $title = null)
 * @method Reset reset(string $name, string $title = null)
 * @method Captcha captcha(CaptchaInterface $captcha)
 * @method Group group(string $title = null)
 * @method File file(string $name, string $label = null)
 * @method TockenSubmit tockenSubmit(string $value)
 * @method Csrf csrf()
 * @method Html html(string $html)
 *
 * @author Enjoys
 */
trait Container
{

    /**
     *
     * @var array objects \Enjoys\Forms\Element
     */
    private array $elements = [];

    public function __call(string $name, array $arguments): Element
    {
        $class_name = '\Enjoys\\Forms\\Elements\\' . ucfirst($name);
        Assert::classExists($class_name);
        /** @var Element $element */
        $element = new $class_name(...$arguments);
        $this->addElement($element);
        return $element;
    }

    /**
     *
     * @param Element $element
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function addElement(Element $element)
    {
        $element->setRequest($this->getRequest());
        if ($element->prepare() !== null) {
            return $this;
        }
        $this->elements[$element->getName()] = $element;
        return $this;
    }


    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     *
     * @param string $name
     * @return Element|null
     */
    public function getElement(string $name): ?Element
    {
        if ($this->elementExists($name)) {
            return $this->elements[$name];
        }

        return null;
    }

    /**
     *
     * @param Element|null $element
     * @return $this
     */
    public function removeElement(?Element $element = null): self
    {
        if (null === $element) {
            return $this;
        }

        if ($this->elementExists($element->getName())) {
            unset($this->elements[$element->getName()]);
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    private function elementExists(string $name): bool
    {
        if (array_key_exists($name, $this->elements) && $this->elements[$name] instanceof Element) {
            return true;
        }
        return false;
    }
}
