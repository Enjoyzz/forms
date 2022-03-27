<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

class Datalist extends Element implements FillableInterface, Ruled
{
    use Fill;
    use Description;
    use Rules;

    protected string $type = 'option';


    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttribute('list', $this->getAttribute('id'));
        $this->removeAttribute('id');
    }

    public function baseHtml(): string
    {
        return '';
    }
}
