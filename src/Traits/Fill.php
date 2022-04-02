<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\AttributeCollection;
use Enjoys\Forms\Element;
use Enjoys\Forms\FillHandler;


trait Fill
{

    private array $elements = [];
    private string $parentName = '';
    /**
     * @var mixed
     */
    private $defaultValue = '';

    public function setParentName(string $parentName): void
    {
        $this->parentName = $parentName;
//        $this->parent = false;
    }

    public function getParentName(): string
    {
        return $this->parentName;
    }

//    public function isParent(): bool
//    {
//        return $this->parent;
//    }

    /**
     * @param array|\Closure $data
     * @param bool $useTitleAsValue
     * @return $this
     * @since 3.4.1 Можно использовать замыкания для заполнения. Анонимная функция должна возвращать массив.
     * @since 3.4.0 Возвращен порядок установки value из индексированных массивов, т.к. неудобно,
     * по умолчанию теперь не надо добавлять пробел в ключи массива, чтобы value был числом
     * но добавлен флаг $useTitleAsValue, если он установлен в true, то все будет работать как в версии 2.4.0
     * @since 2.4.0 Изменен принцип установки value и id из индексированных массивов
     * т.е. [1,2] значения будут 1 и 2 соответственно, а не 0 и 1 как раньше.
     * Чтобы использовать число в качестве value отличное от title, необходимо
     * в массиве конкретно указать значение key. Например ["40 " => test] (обратите внимание на пробел).
     * Из-за того что php преобразует строки, содержащие целое число к int, приходится добавлять
     * пробел либо в начало, либо в конец ключа. В итоге пробелы в начале и в конце удаляются автоматически.
     */
    public function fill($data, bool $useTitleAsValue = false)
    {
        if ($data instanceof \Closure) {
            $data = $data();
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Fill data must be array or closure returned array');
        }

        foreach ($data as $value => $title) {
            $fillHandler = new FillHandler($value, $title, $useTitleAsValue);

            $class = '\Enjoys\Forms\Elements\\' . \ucfirst($this->getType());


            $element = new $class($fillHandler->getValue(), $fillHandler->getLabel());
            $element->setParentName($this->getName());
            $element->setAttrs(Attribute::createFromArray($fillHandler->getAttributes()), 'fill');

            /**
             * @todo слишком много вложенности if. подумать как переделать
             */
            /** @var AttributeCollection $fillCollection */
            $fillCollection = $element->getAttributeCollection('fill');
            foreach ($fillCollection as $attr) {
                if (in_array($attr->getName(), ['id', 'name', 'disabled', 'readonly'])) {
                    $element->setAttr($attr);
                 //   $fillCollection->remove($attr);
                }
            }


            $element->setDefault($this->defaultValue);

            $this->elements[] = $element;
        }
        return $this;
    }

    /**
     *
     * @return array|Element[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }


    public function setDefaultValue(mixed $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }


}
