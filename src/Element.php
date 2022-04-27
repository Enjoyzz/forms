<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Interfaces\ElementInterface;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Traits\Attributes;
use Enjoys\Forms\Traits\Request;

/**
 * Class Element
 * @package Enjoys\Forms
 */
abstract class Element implements ElementInterface
{
    use Attributes;
    use Request;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @var string
     */
    protected string $name;

    /**
     *
     * @var string
     */
    protected string $type = '';

    /**
     *
     * @var string|null
     */
    protected ?string $label = null;

    /**
     * Флаг для обозначения обязательности заполнения этого элемента или нет
     * @var bool
     */
    protected bool $required = false;

    /**
     *
     * @var Form|null
     */
    protected ?Form $form = null;

    /**
     * @param string $name
     * @param string|null $label
     */
    public function __construct(string $name, string $label = null)
    {
        $this->setRequest();
        $this->setName($name);

        if (!is_null($label)) {
            $this->setLabel($label);
        }
    }


    public function setForm(?Form $form): void
    {
        if ($form === null) {
            return;
        }
        $this->form = $form;
        $this->setDefault();
        if ($this instanceof Fillable) {
            foreach ($this->getElements() as $element) {
                $element->setDefault($this->getDefaultValue());
            }
        }
    }

    /**
     *
     * @return Form
     */
    public function getForm(): ?Form
    {
        return $this->form;
    }


    /**
     *
     * @return void
     */
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

    /**
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    protected function setName(string $name): self
    {
        $this->name = $name;
        $this->setAttrs(
            AttributeFactory::createFromArray([
                'id' => $this->name,
                'name' => $this->name
            ])
        );

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @param string|null $title
     * @return $this
     */
    public function setLabel(?string $title = null): self
    {
        $this->label = $title;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return $this
     */
    protected function setDefault(): self
    {
        $value = $this->getForm()
            ->getDefaultsHandler()
            ->getValue($this->getName())
        ;


        if (is_array($value)) {
            $this->setAttr(
                AttributeFactory::create('value', $value[0])
            );
        }

        if (is_string($value) || is_numeric($value)) {
            // $this->setValue($value);
            $this->setAttr(
                AttributeFactory::create('value', $value)
            );
        }
        return $this;
    }

    public function baseHtml(): string
    {
        return "<input type=\"{$this->getType()}\"{$this->getAttributesString()}>";
    }
}
