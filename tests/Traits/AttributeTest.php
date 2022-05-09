<?php

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Traits\Attributes;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{
    public function testAttributesTrait_addAttrs_addAttr_setAttrs_setAttr(): void
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttributes([
            AttributeFactory::create('id', 'id'),
            AttributeFactory::create('class', 'one two'),
        ]);
        $this->assertSame('id="id" class="one two"', (string)$traitAttributes->getAttributeCollection());
        $traitAttributes->setAttributesWithClear([
            AttributeFactory::create('id', 'id')
        ]);
        $this->assertSame('id="id"', (string)$traitAttributes->getAttributeCollection());
        $traitAttributes->addAttribute(AttributeFactory::create('id', 'newid'));
        $traitAttributes->addAttribute(AttributeFactory::create('disabled'));
        $this->assertSame('id="id" disabled', (string)$traitAttributes->getAttributeCollection());
        $traitAttributes->setAttribute(AttributeFactory::create('id', 'newid'));
        $traitAttributes->setAttribute(AttributeFactory::create('class'));
        $this->assertSame('disabled id="newid"', (string)$traitAttributes->getAttributeCollection());
    }

    public function testGetAttributeCollection()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttributes([
            AttributeFactory::create('id', 'id'),
            AttributeFactory::create('class', 'one two'),
        ]);
        $this->assertSame(2, $traitAttributes->getAttributeCollection()->count());
        $this->assertSame(2, $traitAttributes->getAttributes()->count());
    }

    public function testRemoveAttr()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttributes([
            AttributeFactory::create('id', 'id'),
            AttributeFactory::create('class', 'one two'),
        ]);
        $this->assertSame(2, $traitAttributes->getAttributeCollection()->count());
        $returnedObject = $traitAttributes->removeAttribute('id');
        $this->assertSame(1, $traitAttributes->getAttributeCollection()->count());
        $this->assertSame($returnedObject, $traitAttributes);
    }

    public function testAddAttribute()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttribute(AttributeFactory::create('id', '1'));
        $returnedObject = $traitAttributes->addAttribute(AttributeFactory::create('id', '2'));
        $this->assertSame('1', $traitAttributes->getAttribute('id')->getValueString());
        $this->assertSame($returnedObject, $traitAttributes);
    }

    public function testSetAttributes()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttributes(
            AttributeFactory::createFromArray([
                'id' => 'this',
                'name' => 'this'
            ])
        );
        $returnedObject = $traitAttributes->setAttributes(
            AttributeFactory::createFromArray([
                'id' => 'another',
                'name' => 'another'
            ])
        );
        $this->assertSame('id="another" name="another"', $traitAttributes->getAttributes()->__toString());
        $this->assertSame($returnedObject, $traitAttributes);
    }

    public function testAddAttributes()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttributes(
            AttributeFactory::createFromArray([
                'id' => 'this',
                'name' => 'this'
            ])
        );
        $returnedObject = $traitAttributes->addAttributes(
            AttributeFactory::createFromArray([
                'id' => 'another',
                'name' => 'another'
            ])
        );
        $this->assertSame('id="this" name="this"', $traitAttributes->getAttributes()->__toString());
        $this->assertSame($returnedObject, $traitAttributes);
    }

    public function testAddClasses()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $returnedObject = $traitAttributes->addClasses(['1', '2']);
        $this->assertSame('1 2', $traitAttributes->getAttribute('class')->getValueString());
        $this->assertSame($returnedObject, $traitAttributes);
    }

    public function testRemoveClass()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->removeClass('3');
        $this->assertSame(null, $traitAttributes->getAttribute('class'));
        $traitAttributes->addClasses(['1', '2']);
        $this->assertSame('1 2', $traitAttributes->getAttribute('class')->getValueString());
        $returnedObject = $traitAttributes->removeClass('1');
        $this->assertSame('2', $traitAttributes->getAttribute('class')->getValueString());
        $this->assertSame($returnedObject, $traitAttributes);
    }

    public function testGetClassesList()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $this->assertSame([], $traitAttributes->getClassesList());
        $traitAttributes->addClasses(['1', '2']);
        $this->assertSame(['1', '2'], $traitAttributes->getClassesList());
    }

    /**
     * @dataProvider data
     */
    public function testTraitSetAttributes($attributes, $expect): void
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttributes(AttributeFactory::createFromArray($attributes));
        $this->assertEquals($expect, $traitAttributes->getAttributesString());
    }

    public function data()
    {
        return [
            [['test'], ' test'],
            [[54 => 'test'], ' test'],
            [[' 0' => 'test'], '  0="test"'],
            [['test', 'id' => 'test'], ' test id="test"'],
            [
                [
                    'test' => function () {
                        return null;
                    }
                ],
                ' test'
            ],
            [
                [
                    'test' => function () {
                        return 'test';
                    }
                ],
                ' test="test"'
            ],
        ];
    }

    public function testGetAttributesString()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttribute(AttributeFactory::create('first', 'value1'));
        $traitAttributes->setAttribute(AttributeFactory::create('three', 'value3'));
        $this->assertSame(' first="value1" three="value3"', $traitAttributes->getAttributesString());
    }

    public function testAddAttribute_class()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttribute(
            AttributeFactory::create('class', 'value1')
        )->getAttribute('class')->add('value2');
        $this->assertSame(['value1', 'value2'], $traitAttributes->getClassesList());
        $this->assertSame('value1 value2', $traitAttributes->getAttribute('class')->getValueString());

        $traitAttributes->addClasses(
            [
                '1',
                '2'
            ]
        );

        $this->assertSame('value1 value2 1 2', $traitAttributes->getAttribute('class')->getValueString());
    }

    public function testAddAttributeArrays()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttributes(AttributeFactory::createFromArray(['first' => 'value1', 'second' => 'value2']));
        $this->assertSame('value1', $traitAttributes->getAttribute('first')->getValueString());
        $this->assertSame('value2', $traitAttributes->getAttribute('second')->getValueString());

        $traitAttributes->setAttributes(AttributeFactory::createFromArray(['value_withoutkey', 'second' => 'value2']));
        $this->assertSame('value_withoutkey', $traitAttributes->getAttribute('value_withoutkey')->__toString());

        $traitAttributes->addClass('class1');
        $this->assertSame('class1', $traitAttributes->getAttribute('class')->getValueString());

        $traitAttributes->addClass('class2 class3');
        //   $trait->addClass('class1');

        $this->assertSame(
            'class1 class2 class3',
            $traitAttributes->getAttribute('class')->getValueString()
        );

        $this->assertSame(
            [
                'class1',
                'class2',
                'class3',
            ],
            $traitAttributes->getClassesList()
        );


        $traitAttributes->removeClass('class2');
        $this->assertSame(
            'class1 class3',
            $traitAttributes->getAttribute('class')->getValueString()
        );


        $traitAttributes->setAttributes(AttributeFactory::createFromArray(['value_withoutkey', 'second' => 'value2']), 'extra');

        $traitAttributes->addClass('class1', 'extra');
        $traitAttributes->removeClass('class1', 'extra');

        $this->assertSame([], $traitAttributes->getAttribute('class', 'extra')->getValues());


        $this->assertStringContainsString(
            'first="value1" value_withoutkey second="value2" class="class1 class3',
            $traitAttributes->getAttributesString()
        );
        $this->assertStringContainsString(
            'value_withoutkey second="value2"',
            $traitAttributes->getAttributesString('extra')
        );

        $this->assertSame(null, $traitAttributes->getAttribute('value', 'extra2'));
        $this->assertSame('', $traitAttributes->getAttributesString('extra3'));
    }
}
