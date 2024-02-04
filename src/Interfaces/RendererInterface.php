<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

/**
 * @deprecated remove in 6.x
 */
interface RendererInterface
{
    public function output(): mixed;

    public function rendererHiddenElements(): string;

    public function rendererElements(): string;

    public function rendererElement(string $elementName): string;
}
