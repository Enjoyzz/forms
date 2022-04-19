<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{

    public function testSetMin()
    {
        $el = new Date('name');
        $el->setMin((new \DateTimeImmutable("01-01-2022"))->modify("-2 year"));
        $this->assertSame('<input type="date" id="name" name="name" min="2020-01-01">', $el->baseHtml());
    }

    public function testSetMax()
    {
        $el = new Date('name');
        $el->setMax((new \DateTimeImmutable("01-01-2022"))->modify("+2 year"));
        $this->assertSame('<input type="date" id="name" name="name" max="2024-01-01">', $el->baseHtml());
    }
}
