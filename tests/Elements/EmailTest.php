<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{

    public function testSetMultiple()
    {
        $el = new Email('name');
        $el->setMultiple();
        $this->assertSame('<input type="email" id="name" name="name" multiple>', $el->baseHtml());
    }
}
