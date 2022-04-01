<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Traits\Description;

class DescriptionTest
{
    public function test_set_getDescription()
    {
        $mock = $this->getMockForTrait(Description::class);
        $mock->setDescription('desc');
        $this->assertEquals('desc', $mock->getDescription());
        $mock->setDescription();
        $this->assertNull($mock->getDescription());
    }
}
