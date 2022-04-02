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

    public function testSkipSameAttributeName()
    {
        $collection = new AttributeCollection();
        $collection
            ->add(new Attribute('id', 'first'))
            ->add(new Attribute('id', 'second'));
        $this->assertSame('id="first"', (string)$collection);
    }

    public function testReplaceSameAttributeName()
    {
        $collection = new AttributeCollection();
        $collection
            ->add(new Attribute('id', 'first'))
            ->replace(new Attribute('id', 'second'));
        $this->assertSame('id="second"', (string)$collection);
    }

    public function testGetAttributeFormCollection()
    {
        $collection = new AttributeCollection();
        $attrs = Attribute::createFromArray([
            'id' => 'my-id',
            'class' => 'my-class'
        ]);
        foreach ($attrs as $attr) {
            $collection->add($attr);
        }

        $this->assertInstanceOf(Attribute::class, $collection->get('class'));
        $this->assertNull($collection->get('not-found-attribute'));
    }

    public function testHasAttributeInCollection()
    {
        $collection = new AttributeCollection();
        $idAttr = Attribute::create('id');
        $collection->add($idAttr);
        $this->assertTrue($collection->has($idAttr));
        $this->assertFalse($collection->has(Attribute::create('not-found-attribute')));
    }

    public function testRemoveAttributeFromCollection()
    {
        $collection = new AttributeCollection();
        $attrs = Attribute::createFromArray([
            'id' => 'my-id',
            'class' => 'my-class'
        ]);
        foreach ($attrs as $attr) {
            $collection->add($attr);
        }

        $this->assertSame(2, $collection->count());
        $collection->remove('class');
        $this->assertSame(1, $collection->count());
        $collection->remove($collection->get('id'));
        $this->assertSame(0, $collection->count());
    }

    public function testRemoveElementIfNotFound()
    {
        $collection = new AttributeCollection();
        $attrs = Attribute::createFromArray([
            'id' => 'my-id',
            'class' => 'my-class'
        ]);
        foreach ($attrs as $attr) {
            $collection->add($attr);
        }

        $this->assertSame(2, $collection->count());
        $collection->remove('not-found-attribute');
        $this->assertSame(2, $collection->count());
    }


    public function testClearCollection()
    {
        $collection = new AttributeCollection();
        $attrs = Attribute::createFromArray([
            'id' => 'my-id',
            'class' => 'my-class'
        ]);
        foreach ($attrs as $attr) {
            $collection->add($attr);
        }

        $this->assertSame(2, $collection->count());

        $collection->clear();
        $this->assertSame(0, $collection->count());
    }

    public function testToStringIfAttrReturnEmpty()
    {
        $collection = new AttributeCollection();
        $attrs = Attribute::createFromArray([
            'id' => 'my-id',
            'class'
        ]);
        foreach ($attrs as $attr) {
            $collection->add($attr);
        }

        $this->assertSame('id="my-id"', $collection->__toString());
    }


    public function testIterator()
    {
        $collection = new AttributeCollection();
        $attrs = Attribute::createFromArray([
            'id' => 'my-id',
            'class'
        ]);
        foreach ($attrs as $attr) {
            $collection->add($attr);
        }

        foreach ($collection as $item) {
            $this->assertInstanceOf(Attribute::class, $item);
        }
        foreach ($collection->getIterator() as $item) {
            $this->assertInstanceOf(Attribute::class, $item);
        }
    }

}
