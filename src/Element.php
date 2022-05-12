<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Closure;
use Enjoys\Forms\Interfaces\ElementInterface;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Traits\Attributes;
use Enjoys\Forms\Traits\Request;

abstract class Element implements ElementInterface
{
    use Attributes;
    use Request;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected string $name;
    protected string $type = '';

    protected ?string $label = null;

    /**
     * Флаг для обозначения обязательности заполнения этого элемента или нет
     */
    protected bool $required = false;

    protected ?Form $form = null;

    protected bool $allowSameNames = false;


    public function __construct(string $name, string $label = null)
    {
        $this->setRequest();
        $this->setName($name);

        if (!is_null($label)) {
            $this->setLabel($label);
        }
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function setForm(?Form $form): void
    {
        if ($form === null) {
            return;
        }
        $this->form = $form;
        $this->setDefault($this->getForm()->getDefaultsHandler()->getValue($this->getName()));

        if ($this instanceof Fillable) {
            foreach ($this->getElements() as $element) {
                $element->setDefault($this->getDefaultValue());
            }
        }
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function unsetForm(): void
    {
        $this->form = null;
    }

    /**
     * @return true|void
     */
    public function prepare()
    {
        $this->unsetForm();
    }

    public function getType(): string
    {
        return $this->type;
    }

    protected function setName(string $name): ElementInterface
    {
        $this->name = $name;
        $this->setAttributes(
            AttributeFactory::createFromArray([
                'id' => $this->name,
                'name' => $this->name
            ])
        );

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setLabel(?string $title = null): ElementInterface
    {
        $this->label = $title;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    protected function setDefault(mixed $value = null): self
    {
        if (is_array($value)) {
            /** @var array<Closure|scalar|null> $value */
            $this->setAttribute(
                AttributeFactory::create('value', $value[0])
            );
        }

        if (is_string($value) || is_numeric($value)) {
            // $this->setValue($value);
            $this->setAttribute(
                AttributeFactory::create('value', $value)
            );
        }
        return $this;
    }

    public function baseHtml(): string
    {
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}>";
    }

    public function isAllowSameNames(): bool
    {
        return $this->allowSameNames;
    }
}
