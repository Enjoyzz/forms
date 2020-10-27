<?php

declare(strict_types=1);

namespace Enjoys\Forms2;

/**
 * Description of Form
 *
 * @author Enjoys
 */
class Form extends Element
{
    use \Enjoys\Traits\Options;
    use Traits\Container;

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

//    public function render(\Enjoys\Forms2\Renderer\RendererInterface$renderer)
//    {
//        $output = parent::render($renderer);
//        return "<form{$this->getAttributes()}>{$output}</form>";
//    }
    
    public function baseHtml()
    {
        return '';
    }



    protected function setMethod(string $method)
    {
        $this->setAttribute('method', $method);
    }
}
