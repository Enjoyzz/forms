<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Elements;

/**
 * Description of Hidden
 *
 * @author Enjoys
 */
class Hidden extends \Enjoys\Forms2\Element
{

    private $type = 'hidden';

    public function __construct(string $name, string $value = null)
    {
        parent::__construct($name, null);
        
        if (!is_null($value)) {
            $this->setAttribute('value', $value);
        }
        $this->removeAttribute('id');
    }

    public function render()
    {
        return "<input type=\"{$this->type}\"{$this->getAttributes()}>";
    }
}
