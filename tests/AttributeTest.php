<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Attribute;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class AttributeTest extends TestCase
{
    public function testAddAttributeNotMultiple()
    {
        $attr = new Attribute('id', 'my id with space');
        $this->assertCount(1, $attr->getValues());
        $this->assertSame('id="my id with space"', $attr->__toString());
        $attr->add(1);
        $this->assertCount(1, $attr->getValues());
        $this->assertSame('id="1"', $attr->__toString());
    }

    public function testAddAttributeAutoMultiple()
    {
        $attr = new Attribute('class', 'many classes defined by space');
        $this->assertCount(5, $attr->getValues());
        $this->assertSame('class="many classes defined by space"', $attr->__toString());
        $attr->add('more class');
        $this->assertCount(7, $attr->getValues());
        $this->assertSame('class="many classes defined by space more class"', $attr->__toString());
        $attr->add('repeat more class');
        $this->assertCount(8, $attr->getValues());
        $this->assertSame('class="many classes defined by space more class repeat"', $attr->__toString());
    }

    public function testAddAttributeAutoMultipleWithSetSeparator()
    {
        $attr = new Attribute('class', 'many classes defined by space');
        $attr->setSeparator('-');
        $this->assertCount(5, $attr->getValues());
        $this->assertSame('class="many-classes-defined-by-space"', $attr->__toString());
        $attr->add('must-exploded');
        $this->assertCount(7, $attr->getValues());
        $this->assertSame('class="many-classes-defined-by-space-must-exploded"', $attr->__toString());
        $attr->remove('defined');
        $attr->remove('classes');
        $attr->remove('by');
        $attr->add('repeat must be division by space');
        $this->assertCount(5, $attr->getValues());
        $this->assertSame('class="many-space-must-exploded-repeat must be division by space"', $attr->__toString());
    }

    public function testAttributeWithoutValue()
    {
        $attr = new Attribute('disabled');
        $this->assertSame('disabled', $attr->__toString());
        $attr->setFillNameAsValue(true);
        $this->assertSame('disabled="disabled"', $attr->__toString());
        $attr->setWithoutValue(false);
        $this->assertSame('', $attr->__toString());

        $attr = new Attribute('class');
        $this->assertSame('', $attr->__toString());
    }

    public function testSet()
    {
        $attr = new Attribute('id', 1);
        $attr->set([2]);
        $this->assertSame('2', $attr->getValueString());
    }

    public function testClone()
    {
        $attr = new Attribute('id', 42);
        $new = $attr->withName('baz');
        $this->assertSame('42', $new->getValueString());
    }

    public function testCreateFromArrayWithIntNameAndStringValue()
    {
        $attr = Attribute::createFromArray([
            'test'
        ]);
        self::assertSame([], current($attr)->getValues());
        self::assertSame('test', current($attr)->__toString());
    }

    public function testNormalizeClosure()
    {
        $attr = new Attribute('id');
        $attr->add(function (){
            return 42;
        });
        $this->assertSame('42', $attr->getValueString());
    }

    public function testRemoveTrue()
    {
        $attr = new Attribute('id', 42);
        $this->assertSame('42', $attr->getValueString());
        $this->assertTrue($attr->remove('42'));
        $this->assertSame('', $attr->getValueString());
        $this->assertFalse($attr->remove('invalid'));
    }

    public function testCloneAttributeWithName()
    {
        $attr = new Attribute('id', 42);
        $newAttr = $attr->withName('ids');
        $this->assertSame('id', $attr->getName());
        $this->assertSame('ids', $newAttr->getName());

    }

    public function testAttributeCreateStaticFunction()
    {
        $attr = Attribute::create('id', 42);
        $this->assertSame(['42'], $attr->getValues());
    }

    public function testSetMultipleAttribute()
    {
        $attr = new Attribute('id', 'one');
        $attr->add('two');
        $this->assertCount(1, $attr->getValues());
        $this->assertSame(['two'], $attr->getValues());
        $attr->setMultiple(true);
        $attr->add('one');
        $attr->add('three');
        $this->assertSame(['two', 'one', 'three'], $attr->getValues());
        $this->assertCount(3, $attr->getValues());

    }

    public function testSetValuesToAttribute()
    {
        $attr = new Attribute('id', 1);
        $attr->setMultiple(true);
        $attr->set([2,3]);
        $this->assertSame(['2','3'], $attr->getValues());
    }

    public function testClearAttributes()
    {
        $attr = new Attribute('class', '1 2 3');
        $this->assertCount(3, $attr->getValues());
        $attr->clear();
        $this->assertCount(0, $attr->getValues());
    }

    public function testHasAttribute()
    {
        $attr = new Attribute('class', '1 2 3');
        $this->assertTrue($attr->has('2'));
        $this->assertFalse($attr->has('42'));
    }

    public function testClosureAttributeValue()
    {
        $attr = new Attribute('id');
        $attr->add(function (){
           return 42;
        });
        $this->assertTrue($attr->has('42'));
    }

    public function testInvalidClosureAttributeValue()
    {
        $this->expectException(InvalidArgumentException::class);
        new Attribute('id', function (){
            return new \stdClass();
        });
    }
}
