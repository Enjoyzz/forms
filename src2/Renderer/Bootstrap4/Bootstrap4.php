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
    use \Enjoys\Traits\Options;

    private $element;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

  
    public function render(\Enjoys\Forms2\Element $element)
    {
         $this->element = $element;
         return $this;
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

    public function base()
    {
        switch (\get_class($this->element)) {
            case \Enjoys\Forms2\Elements\Checkbox::class:
                $base = new RenderCheckbox($this->element);
                break;
            case \Enjoys\Forms2\Elements\Radio::class:
                $base = new RenderRadio($this->element);
                break;
            case \Enjoys\Forms2\Elements\File::class:
                $base = new RenderFile($this->element);
                break;
            default:
                $base = new RenderInput($this->element);
                break;
        }

        return (string) $base;
    }

    public function html()
    {
        
    }

    public function __toString(): string
    {
        return '<div class="form-group"> ' .
                $this->label() .
                $this->base() .
                $this->description() .
                '</div>';
    }
}
