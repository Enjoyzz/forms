<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Number;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class NumberTest extends TestCase
{

    public function testSetMax()
    {
        $el = new Number('name');
        $el->setMax(10);
        $this->assertSame('<input type="number" id="name" name="name" max="10">', $el->baseHtml());

        $el->setMax('8');
        $this->assertSame('<input type="number" id="name" name="name" max="8">', $el->baseHtml());

        $el->setMax('9.5');
        $this->assertSame('<input type="number" id="name" name="name" max="9">', $el->baseHtml());

        $this->expectException(InvalidArgumentException::class);
        $el->setMax('invalid');
    }

    public function testSetStep()
    {
        $el = new Number('name');
        $el->setStep(10);
        $this->assertSame('<input type="number" id="name" name="name" step="10">', $el->baseHtml());

        $el->setStep(.1);
        $this->assertSame('<input type="number" id="name" name="name" step="0.1">', $el->baseHtml());

        $el->setStep('8');
        $this->assertSame('<input type="number" id="name" name="name" step="8">', $el->baseHtml());

        $el->setStep('9.5');
        $this->assertSame('<input type="number" id="name" name="name" step="9.5">', $el->baseHtml());

        $this->expectException(InvalidArgumentException::class);
        $el->setStep('invalid');
    }

    public function testSetMin()
    {
        $el = new Number('name');
        $el->setMin(10);
        $this->assertSame('<input type="number" id="name" name="name" min="10">', $el->baseHtml());

        $el->setMin('8');
        $this->assertSame('<input type="number" id="name" name="name" min="8">', $el->baseHtml());

        $el->setMin('9.5');
        $this->assertSame('<input type="number" id="name" name="name" min="9">', $el->baseHtml());

        $this->expectException(InvalidArgumentException::class);
        $el->setMin('invalid');
    }
}
