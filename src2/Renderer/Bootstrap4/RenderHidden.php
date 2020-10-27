<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer\Bootstrap4;

/**
 * Description of RenderHidden
 *
 * @author Enjoys
 */
class RenderHidden
{
    private $renderer;

    public function __construct(Bootstrap4 $renderer)
    {
        $this->renderer = $renderer;
    }
    
    public function __toString()
    {
        return $this->renderer->baseHtml();
    }
}
