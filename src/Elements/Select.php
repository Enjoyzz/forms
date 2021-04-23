<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

/**
 * Class Select
 * @package Enjoys\Forms\Elements
 */
class Select extends Element implements FillableInterface
{
    use Fill;
    use Description;
    use Rules;

    /**
     *
     * @var string
     */
    protected string $type = 'option';



    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
    }

    public function setMultiple(): self
    {
        $this->setAttribute('multiple');
        return $this;
    }

    private function isMultiple(): void
    {
        if ($this->getAttribute('multiple') !== false && \substr($this->getName(), -2) !== '[]') {
            $_id = $this->getAttribute('id');
            $this->setName($this->getName() . '[]');
            $this->setParentName($this->getName());
            //т.к. id уже переписан ,восстанавливаем его
            $this->setAttributes([
                'id' => $_id
            ]);
        }
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     * @param string $name
     * @param string|null|false $value
     * @param string $namespace
     * @return $this
     */
    public function setAttribute(string $name, $value = null, string $namespace = 'general'): self
    {
        parent::setAttribute($name, $value, $namespace);
        $this->isMultiple();
        return $this;
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     * @param array $attributes
     * @param string $namespace
     * @return $this
     */
    public function setAttributes(array $attributes, string $namespace = 'general'): self
    {
        parent::setAttributes($attributes, $namespace);
        $this->isMultiple();
        return $this;
    }

    /**
     *
     * @return $this
     */
    protected function setDefault(): self
    {
        $this->defaultValue = $this->getForm()->getDefaultsHandler()->getValue($this->getName());
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
     *
     */
    public function setOptgroup(string $label, array $data = [], array $attributes = [], $useTitleAsValue = false): self
    {
        $optgroup = new Optgroup($label, $this->getName(), $this->defaultValue);
        $optgroup->setAttributes($attributes);
        $optgroup->fill($data, $useTitleAsValue);
        $this->elements[] = $optgroup;
        return $this;
    }
}
