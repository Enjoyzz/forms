<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Element;
use Enjoys\Forms\Elements;
use Enjoys\Forms\Interfaces\CaptchaInterface;
use Enjoys\Forms\Interfaces\ElementInterface;
use Webmozart\Assert\Assert;

/**
 * @method Elements\Text text(string $name, string $label = null)
 * @method Elements\Hidden hidden(string $name, string $value = null)
 * @method Elements\Password password(string $name, string $label = null)
 * @method Elements\Submit submit(string $name, string $title = null)
 * @method Elements\Header header(string $title = null)
 * @method Elements\Color color(string $name, string $label = null)
 * @method Elements\Date date(string $name, string $label = null)
 * @method Elements\Datetime datetime(string $name, string $label = null)
 * @method Elements\Datetimelocal datetimelocal(string $name, string $label = null)
 * @method Elements\Email email(string $name, string $label = null)
 * @method Elements\Number number(string $name, string $label = null)
 * @method Elements\Range range(string $name, string $label = null)
 * @method Elements\Search search(string $name, string $label = null)
 * @method Elements\Tel tel(string $name, string $label = null)
 * @method Elements\Time time(string $name, string $label = null)
 * @method Elements\Url url(string $name, string $label = null)
 * @method Elements\Month month(string $name, string $label = null)
 * @method Elements\Week week(string $name, string $label = null)
 * @method Elements\Textarea textarea(string $name, string $label = null)
 * @method Elements\Select select(string $name, string $label = null)
 * @method Elements\Button button(string $name, string $title = null)
 * @method Elements\Datalist datalist(string $name, string $label = null)
 * @method Elements\Checkbox checkbox(string $name, string $label = null)
 * @method Elements\Image image(string $name, string $src = null)
 * @method Elements\Radio radio(string $name, string $title = null)
 * @method Elements\Reset reset(string $name, string $title = null)
 * @method Elements\Captcha captcha(CaptchaInterface $captcha)
 * @method Elements\Group group(string $title = null)
 * @method Elements\File file(string $name, string $label = null)
 * @method Elements\Csrf csrf()
 * @method Elements\Html html(string $html)
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
            && false !== $key = $this->getElementKey($element)
        ) {
            $this->elements[$key] = $element;
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

    public function getElement(string $name): ?Element
    {
        if (false !== $key = $this->getElementKey($name)) {
            return $this->elements[$key];
        }

        return null;
    }

    /**
     * @param Element|null $element
     * @return $this
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function removeElement(?Element $element = null)
    {
        if (null === $element) {
            return $this;
        }

        if (false !== $key = $this->getElementKey($element)) {
            unset($this->elements[$key]);
        }
        return $this;
    }

    private function getElementKey(string|Element $element): int|false
    {
        $name = ($element instanceof Element) ? $element->getName() : $element;

        foreach ($this->elements as $key => $el) {
            if ($el->getName() === $name) {
                /** @var int $key */
                return $key;
            }
        }
        return false;
    }


}
