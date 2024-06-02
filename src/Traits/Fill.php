<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Closure;
use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\FillHandler;
use Enjoys\Forms\Interfaces\AttributeInterface;
use Enjoys\Forms\Interfaces\Fillable;
use InvalidArgumentException;

use function ucfirst;

trait Fill
{
    /**
     * @var array<array-key, Element&Fillable>
     */
    private array $elements = [];
    private string $parentName = '';
    /**
     * @var mixed
     */
    private mixed $defaultValue = '';

    public function setParentName(string $parentName): void
    {
        $this->parentName = $parentName;
    }

    public function getParentName(): string
    {
        return $this->parentName;
    }

    /**
     * @since 3.4.1 Можно использовать замыкания для заполнения. Анонимная функция должна возвращать массив.
     * @since 3.4.0 Возвращен порядок установки value из индексированных массивов, т.к. неудобно,
     * По умолчанию теперь не надо добавлять пробел в ключи массива, чтобы value был числом,
     * но добавлен флаг $useTitleAsValue, если он установлен в true, то все будет работать как в версии 2.4.0
     * @since 2.4.0 Изменен принцип установки value и id из индексированных массивов
     * т.е. [1,2] значения будут 1 и 2 соответственно, а не 0 и 1 как раньше.
     * Чтобы использовать число в качестве value отличное от title, необходимо
     * в массиве конкретно указать значение key. Например, ["40 " => test] (обратите внимание на пробел).
     * Из-за того что php преобразует строки, содержащие целое число к int, приходится добавлять
     * пробел либо в начало, либо в конец ключа. В итоге пробелы в начале и в конце удаляются автоматически.
     */
    public function fill(array|Closure $data, bool $useTitleAsValue = false): static
    {
        if ($data instanceof Closure) {
            /** @var mixed $data */
            $data = $data();
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('Fill data must be array or closure returned array');
        }

        /** @var scalar|array $title */
        foreach ($data as $value => $title) {
            $fillHandler = new FillHandler($value, $title, $useTitleAsValue);

            /** @var class-string<Fillable&Element> $class */
            $class = '\Enjoys\Forms\Elements\\' . ucfirst($this->getType());

            $element = new $class($fillHandler->getValue(), $fillHandler->getLabel(), false);

            $element->setAttributes(AttributeFactory::createFromArray($fillHandler->getAttributes()), 'fill');

            $fillCollection = $element->getAttributeCollection('fill');

            /** @var AttributeInterface $attribute */
            foreach ($fillCollection as $attribute) {
                $element->setAttribute($attribute);
            }

            $this->addElement($element);
        }
        return $this;
    }

    /**
     * @psalm-return array<array-key, Element&Fillable>
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }


    public function setDefaultValue(mixed $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }


    /**
     * @param Fillable&Element $element
     */
    public function addElement(Fillable $element): static
    {
        $element->setParentName($this->getName());
        $element->setDefault($this->getDefaultValue());
        $this->elements[] = $element;
        return $this;
    }

    /**
     * @param array<Fillable&Element> $elements
     */
    public function addElements(array $elements): static
    {
        foreach ($elements as $element) {
            $this->addElement($element);
        }
        return $this;
    }
}
