<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Traits\Description;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    public function test_set_getDescription()
    {
        /** @var Description $mock */
        $mock = $this->getMockForTrait(Description::class);
        $mock->setDescription('desc');
        $this->assertEquals('desc', $mock->getDescription());
        $mock->setDescription();
        $this->assertNull($mock->getDescription());
    }

    public function testFluentDescription()
    {
        $el = new Text('test');
        $el->setDescription('desc')
            ->setLabel('label')
        ;
        $this->assertSame('desc', $el->getDescription());
        $this->assertSame('label', $el->getLabel());
    }
}
