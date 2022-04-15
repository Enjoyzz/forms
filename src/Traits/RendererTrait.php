<?php

declare(strict_types=1);

namespace Enjoys\Forms\Traits;

use Enjoys\Forms\Form;

trait RendererTrait
{
    private Form $form;

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
