<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Enjoys\Forms\Form;

interface RendererInterface
{
    public function setForm(Form $form): Form;

    public function output(): mixed;

    public function rendererHiddenElements(): string;

    public function rendererElements(): string;

    public function rendererElement(string $elementName): string;
}
