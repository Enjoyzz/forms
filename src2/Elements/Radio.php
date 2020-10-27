<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Elements;

use Enjoys\Forms2\ElementContainer;
use Enjoys\Forms2\Traits\Fill;

/**
 * Class Radio
 *
 * @author Enjoys
 *
 */
class Radio extends ElementContainer
{
    use Fill;

    /**
     *
     * @var string
     */
    protected string $type = 'radio';
    private static $prefix_id = 'rb_';
    protected $needParent = true;

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttributes([
            'value' => $name,
            'id' => $this->getPrefixId() . $name
        ]);
        //$this->removeAttribute('name');
    }

    public function prepare()
    {
        $this->setAttributes([
            'name' => $this->getParent()->getName()
        ]);

        $this->unsetParent();
    }

    public function setPrefixId($prefix)
    {
        static::$prefix_id = $prefix;
        $this->setId(static::$prefix_id . $this->getName());
        return $this;
    }

    public function getPrefixId()
    {
        return static::$prefix_id;
    }

    public function baseHtml()
    {
        return "<input type=\"{$this->type}\"{$this->getAttributes()}>";
    }
//    public function setDefault(): self
//    {
//       // $value = Arrays::getValueByIndexPath($this->getParentName(), $this->formDefaults->getDefaults());
//        $value = $this->form->getFormDefaults()->getValue($this->getParentName());
//
//        if (is_array($value)) {
//            if (in_array($this->getAttribute('value'), $value)) {
//                $this->setAttribute('checked');
//                return $this;
//            }
//        }
//
//        if (is_string($value) || is_numeric($value)) {
//            if ($this->getAttribute('value') == $value) {
//                $this->setAttribute('checked');
//                return $this;
//            }
//        }
//        return $this;
//    }
}
