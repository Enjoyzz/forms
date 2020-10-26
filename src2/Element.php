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
        return $this;
    }

    abstract public function render();
}
