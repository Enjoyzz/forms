<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Elements;

/**
 * Description of Checkbox
 *
 * @author deadl
 */
class Checkbox extends \Enjoys\Forms2\Composite
{
    // use Fill;
    use \Enjoys\Forms2\Traits\Fill;

    /**
     *
     * @var string
     */
    private string $type = 'checkbox';
    private static $prefix_id = 'cb_';
    protected $needParent = true;

    public function __construct(string $name, string $title = null)
    {
        $construct_name = $name;
        if (\substr($name, -2) !== '[]') {
            $construct_name = $name . '[]';
        }
        parent::__construct($construct_name, $title);

        $this->setAttributes([
            'value' => $name,
            'id' => $this->getPrefixId() . $name,
        ]);

       // $this->removeAttribute('name');
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

    public function render()
    {
        $output = '';
        foreach ($this->elements as $element) {
            $output .= "<input type=\"{$element->type}\"{$element->getAttributes()}>";
        }
        return $output;
        
    
    }
//    public function setDefault(): self
//    {
//
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
