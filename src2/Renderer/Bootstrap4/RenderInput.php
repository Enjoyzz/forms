<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer\Bootstrap4;

/**
 * Description of RenderInput
 *
 * @author Enjoys
 */
class RenderInput
{

    private $renderer;

    public function __construct(Bootstrap4 $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __toString()
    {
        $this->renderer->getElement()->addClass('form-control');

        return '<div class="form-group"> ' .
                $this->renderer->label() .
                $this->renderer->getElement()->baseHtml() .
                $this->renderer->description() .
                '</div>';
    }
}
