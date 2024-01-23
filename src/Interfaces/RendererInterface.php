<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

interface RendererInterface
{
    public function output(): mixed;

    public function rendererHiddenElements(): string;

    public function rendererElements(): string;

    public function rendererElement(string $elementName): string;
}
