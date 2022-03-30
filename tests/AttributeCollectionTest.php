<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\AttributeCollection;
use PHPUnit\Framework\TestCase;

class AttributeCollectionTest extends TestCase
{

    public function testAddToCollection()
    {
        $collection = new AttributeCollection();
        $collection->add(new Attribute('id', 'my-id'));
        $this->assertCount(1, $collection);
    }

    public function testGetStringAttributes()
    {
        $collection = new AttributeCollection();
        $collection->add(new Attribute('id', 'my-id'));
        $collection->add(new Attribute('class', 'one_class two_class'));
        $this->assertSame('id="my-id" class="one_class two_class"', $collection->__toString());
    }
}
