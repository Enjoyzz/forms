<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Fillable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Forms\Traits\Rules;

class Datalist extends Element implements Fillable, Ruleable, Descriptionable
{
    use Fill;
    use Description;
    use Rules;

    protected string $type = 'option';


    public function __construct(string $name, ?string $title = null)
    {
        parent::__construct($name, $title);
        $this->setAttribute(AttributeFactory::create('list', $name . '-list'));
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function baseHtml(): string
    {
        $return = sprintf(
            "<input%s>\n<datalist id='%s'>\n",
            $this->getAttributesString(),
            $this->getAttribute('list')->getValueString()
        );

        foreach ($this->getElements() as $data) {
            //$return .= "<option value=\"{$data->getLabel()}\">";
            $data->setAttribute(AttributeFactory::create('value', $data->getLabel()));
            $data->setLabel(null);
            $return .= $data->baseHtml() . PHP_EOL;
        }
        $return .= "</datalist>";
        return $return;
    }
}
