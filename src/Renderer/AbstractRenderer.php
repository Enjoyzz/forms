<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer;

use Enjoys\Forms\Form;
use Enjoys\Forms\Interfaces\RendererInterface;

abstract class AbstractRenderer implements RendererInterface
{
    private Form $form;

    public function __construct(Form $form = null)
    {
        $this->form = $form ?? new Form();
    }

    abstract public function output(): mixed;

    public function setForm(Form $form): Form
    {
        $this->form = $form;
        return $this->form;
    }

    public function getForm(): Form
    {
        return $this->form;
    }
}
