<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\AttributeCollection;
use Enjoys\Forms\Elements\Text;
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

    public function testAttributesTrait_addAttrs_addAttr_setAttrs_setAttr()
    {
        $text = new Text('test');
        $this->assertSame('id="test" name="test"', (string)$text->getAttributeCollection());
        $text->addAttrs([
            new Attribute('id', 'id'),
            new Attribute('class', 'one two'),
        ]);
        $this->assertSame('id="test" name="test" class="one two"', (string)$text->getAttributeCollection());
        $text->setAttrsWithClear([
            new Attribute('id', 'id')
        ]);
        $this->assertSame('id="id"', (string)$text->getAttributeCollection());
        $text->addAttr(new Attribute('id', 'newid'));
        $text->addAttr(new Attribute('disabled'));
        $this->assertSame('id="id" disabled', (string)$text->getAttributeCollection());
        $text->setAttr(new Attribute('id', 'newid'));
        $text->setAttr(new Attribute('class'));
        $this->assertSame('disabled id="newid"', (string)$text->getAttributeCollection());
    }
}
