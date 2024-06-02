<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\AttributeInterface;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

class Select extends Element implements Fillable, Ruleable, Descriptionable
{
    use Fill;
    use Description;
    use Rules;

    protected string $type = 'option';

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
    }

    public function setMultiple(): Select
    {
        $this->setAttribute(AttributeFactory::create('multiple'));
        return $this;
    }

    private function isMultiple(): void
    {
        if ($this->getAttribute('multiple') !== null && !str_ends_with($this->getName(), '[]')) {
            $id = $this->getAttribute('id') ?? AttributeFactory::create('id', $this->getName());
            $this->setName($this->getName() . '[]');
            //т.к. id уже переписан ,восстанавливаем его
            $this->setAttribute($id);
        }
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     */
    public function setAttribute(AttributeInterface $attribute, string $namespace = 'general'): static
    {
        parent::setAttribute($attribute, $namespace);
        $this->isMultiple();
        return $this;
    }


    protected function setDefault(mixed $value = null): static
    {
        $this->setDefaultValue($value);
        return $this;
    }

    /**
     * @param string $label Аттрибут label для optgroup
     * @param array $data Массив для заполнения в функции fill()
     * @param array $attributes Аттрибуты для optgroup (id и name аттрибуты автоматически удалены)
     * @param bool $useTitleAsValue
     * @return $this
     * @since 2.4.0
     * @since 3.4.0 added $useTitleAsValue, see Trait\Fill
     */
    public function setOptgroup(
        string $label,
        array $data = [],
        array $attributes = [],
        bool $useTitleAsValue = false
    ): Select {
        $optgroup = new Optgroup($label, $this->getName(), $this->defaultValue);
        $optgroup->setAttributes(AttributeFactory::createFromArray($attributes));
        $optgroup->fill($data, $useTitleAsValue);
        $this->elements[] = $optgroup;
        return $this;
    }
}
