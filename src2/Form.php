<?php

declare(strict_types=1);

namespace Enjoys\Forms2;

/**
 * Description of Form
 *
 * @author Enjoys
 */
class Form extends ElementContainer
{
    use \Enjoys\Traits\Options;

    public const ATTRIBUTES_DESC = '_desc_attributes_';
    public const ATTRIBUTES_VALIDATE = '_validate_attributes_';
    public const ATTRIBUTES_LABEL = '_label_attributes_';
    public const ATTRIBUTES_FIELDSET = '_fieldset_attributes_';

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
        $name = $this->name ?? \uniqid('form');
        parent::__construct($name, null);


        $this->hidden('token', 'toooodfjhashsahdvsd');
    }

    public function render(\Enjoys\Forms2\Renderer\RendererInterface$renderer)
    {
        $output = parent::render($renderer);
        return "<form{$this->getAttributes()}>{$output}</form>";
    }
    
    public function baseHtml()
    {
        return '';
    }

    /**
     * 
     * @method \Enjoys\Forms2\Elements\Text text($name, $label = null)
     * @method \Enjoys\Forms2\Elements\File file($name, $label = null)
     * 
     * @param string $name
     * @param array $arguments
     * @return @method
     * @throws Exception\ExceptionElement
     */
    public function __call(string $name, array $arguments)
    {
        $class_name = '\Enjoys\\Forms2\Elements\\' . ucfirst($name);
        if (!class_exists($class_name)) {
            throw new Exception\ExceptionElement("Class <b>{$class_name}</b> not found");
        }
        /** @var Element $element */
        $element = new $class_name(...$arguments);
        return $this->add($element);
        //return $element;
    }

    private function setMethod(string $method)
    {
        $this->setAttribute('method', $method);
    }
}
