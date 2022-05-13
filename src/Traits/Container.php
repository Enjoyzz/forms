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
use Enjoys\Forms\Elements\Url;
use Enjoys\Forms\Elements\Week;
use Enjoys\Forms\Interfaces\CaptchaInterface;
use Enjoys\Forms\Interfaces\ElementInterface;
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
 * @method Csrf csrf()
 * @method Html html(string $html)
 *
 * @author Enjoys
 */
trait Container
{
    /**
     * @var Element[]
     */
    private array $elements = [];


    public function __call(string $name, array $arguments): Element
    {
        $className = '\Enjoys\\Forms\\Elements\\' . ucfirst($name);
        Assert::classExists($className);
        /** @var class-string<ElementInterface> $className */
        $element = new $className(...$arguments);

        /** @var Element $element */
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
        if ($element->prepare() === true) {
            return $this;
        }


        if ($element->isAllowSameNames() === false
            && false !== $keyElement = $this->getElementKeyByName($element->getName())
        ) {
            $this->elements[$keyElement] = $element;
            return $this;
        }


        $this->elements[] = $element;
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
        if (false !== $key = $this->getElementKeyByName($name)) {
            return $this->elements[$key];
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

        $key = $this->getElementKeyByName($element->getName());

        if ($key !== false) {
            unset($this->elements[$key]);
        }
        return $this;
    }

    private function getElementKeyByName(string $name): int|false
    {
        foreach ($this->elements as $key => $element) {
            if ($element->getName() === $name) {
                /** @var int $key */
                return $key;
            }
        }
        return false;
    }


}
