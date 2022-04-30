<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Description;
use Enjoys\Forms\Traits\Rules;
use Webmozart\Assert\Assert;

class Number extends Element implements Ruleable, Descriptionable
{
    use Description;
    use Rules;

    protected string $type = 'number';


    public function setMin(string|int $min): self
    {
        Assert::numeric($min);
        $min = (int) $min;
        $this->setAttribute(AttributeFactory::create('min', $min));
        return $this;
    }


    public function setMax(int|string $max): self
    {
        Assert::numeric($max);
        $max = (int) $max;
        $this->setAttribute(AttributeFactory::create('max', $max));
        return $this;
    }


    /**
     * @param numeric $step
     * @return $this
     */
    public function setStep(float|int|string $step): self
    {
        Assert::numeric($step);
        $this->setAttribute(AttributeFactory::create('step', $step));
        return $this;
    }
}
