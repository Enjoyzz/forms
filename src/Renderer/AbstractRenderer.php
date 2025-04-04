<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer;

use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\RendererInterface;

/**
 * @deprecated use \Enjoys\Forms\Renderer\Renderer, remove in 6.x
 */
abstract class AbstractRenderer implements RendererInterface
{
    private Form $form;

    public function __construct(?Form $form = null)
    {
        $this->form = $form ?? new Form();
    }

    abstract public function output(): mixed;

    public function setForm(Form $form): self
    {
        $this->form = $form;
        return $this;
    }

    public function getForm(): Form
    {
        return $this->form;
    }
}
