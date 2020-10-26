<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Elements;

/**
 * Description of newPHPClass
 *
 * @author Enjoys
 */
class Text extends \Enjoys\Forms2\Element
{
    private $type = 'text';
    
    public function render()
    {
        return "<input type=\"{$this->type}\"{$this->getAttributes()}>";
    }
}
