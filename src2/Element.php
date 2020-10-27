<?php

declare(strict_types=1);

namespace Enjoys\Forms2;

/**
 * Description of Element
 *
 * @author Enjoys
 */
abstract class Element
{
    use Traits\Attributes;

    protected $name;
    protected $title;
    protected $defaults;
    protected $parent;
    protected $needParent = false;

    /**
     * Когда $rule_error === true выводится это сообщение
     * @var string|null
     */
    private ?string $rule_error_message = null;

    /**
     * Если true - проверка validate не пройдена
     * @var bool
     */
    private bool $rule_error = false;

    /**
     * Список правил для валидации
     * @var array
     */
    protected array $rules = [];

    public function __construct(string $name, string $title = null)
    {

        $this->name = $name;
        $this->title = $title;

        $this->setAttributes([
            'name' => $this->getName(),
            'id' => $this->getName(),
        ]);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDefaults($data)
    {
        $this->defaults = $data;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    public function setParent(Element $element)
    {
        $this->parent = $element;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function unsetParent()
    {
        $this->parent = null;
    }

    public function needParent()
    {
        return $this->needParent;
    }

    public function prepare()
    {
        return;
    }

    public function isComposite()
    {
        return false;
    }

    public function render(\Enjoys\Forms2\Renderer\RendererInterface $renderer)
    {
        return $renderer->render($this);
    }
    
    abstract public function baseHtml();
}
