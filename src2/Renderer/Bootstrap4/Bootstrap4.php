<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer\Bootstrap4;

/**
 * Description of Bootstrap4
 *
 * @author Enjoys
 */
class Bootstrap4 implements \Enjoys\Forms2\Renderer\RendererInterface
{
    
    private $element;

    public function __construct($element, array $options)
    {
        $this->setOptions($options);
        $this->element = $element;
        dump($this->element);
    }

    public function render()
    {
        switch (\get_class($this->element)) {
            case \Enjoys\Forms2\Elements\Form::class:
                $rendered = new RenderForm($this);
                break;
            case \Enjoys\Forms2\Elements\Checkbox::class:
                $rendered = new RenderCheckbox($this);
                break;
            case \Enjoys\Forms2\Elements\Radio::class:
                $rendered = new RenderRadio($this);
                break;
            case \Enjoys\Forms2\Elements\File::class:
                $rendered = new RenderFile($this);
                break;
            case \Enjoys\Forms2\Elements\Hidden::class:
                $rendered = new RenderHidden($this);
                break;
            default:
                $rendered = new RenderInput($this);
                break;
        }
        return $rendered;
    }

    public function label(): string
    {
        $label = new \Enjoys\Forms2\Renderer\RenderLabel($this->element);
        return (string) $label;
    }

    public function description(): string
    {
        $this->element->setAttributes([
            'id' => $this->element->getAttribute('id') . 'Help',
            'class' => 'form-text text-muted'
                ], \Enjoys\Forms2\Form::ATTRIBUTES_DESC);
        $this->element->setAttributes([
            'aria-describedby' => $this->element->getAttribute('id', \Enjoys\Forms2\Form::ATTRIBUTES_DESC)
        ]);

        $description = new \Enjoys\Forms2\Renderer\RenderDescription($this->element);
        return (string) $description;
    }

    public function getElement()
    {
        return $this->element;
    }

    public function __toString(): string
    {
//        return '<div class="form-group"> ' .
//                $this->label() .
//                $this->base() .
//                $this->description() .
//                '</div>';
        


        return (string) $this->render();        
    }
}
