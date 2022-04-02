<?php

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Traits\Attributes;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{


    public function testAttributesTrait_addAttrs_addAttr_setAttrs_setAttr(): void
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttrs([
            new Attribute('id', 'id'),
            new Attribute('class', 'one two'),
        ]);
        $this->assertSame('id="id" class="one two"', (string)$traitAttributes->getAttributeCollection());
        $traitAttributes->setAttrsWithClear([
            new Attribute('id', 'id')
        ]);
        $this->assertSame('id="id"', (string)$traitAttributes->getAttributeCollection());
        $traitAttributes->addAttr(new Attribute('id', 'newid'));
        $traitAttributes->addAttr(new Attribute('disabled'));
        $this->assertSame('id="id" disabled', (string)$traitAttributes->getAttributeCollection());
        $traitAttributes->setAttr(new Attribute('id', 'newid'));
        $traitAttributes->setAttr(new Attribute('class'));
        $this->assertSame('disabled id="newid"', (string)$traitAttributes->getAttributeCollection());
    }

    public function testGetAttributeCollection()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttrs([
            new Attribute('id', 'id'),
            new Attribute('class', 'one two'),
        ]);
        $this->assertSame(2, $traitAttributes->getAttributeCollection()->count());
        $this->assertSame(2, $traitAttributes->getAttrs()->count());
    }

    public function testRemoveAttr()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->addAttrs([
            new Attribute('id', 'id'),
            new Attribute('class', 'one two'),
        ]);
        $this->assertSame(2, $traitAttributes->getAttributeCollection()->count());
        $traitAttributes->removeAttr('id');
        $this->assertSame(1, $traitAttributes->getAttributeCollection()->count());
    }

    /**
     * @dataProvider data
     */
    public function testTraitSetAttributes($attributes, $expect): void
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttrs(Attribute::createFromArray($attributes));
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
        $traitAttributes->setAttr(Attribute::create('first', 'value1'));
        $traitAttributes->setAttr(Attribute::create('three', 'value3'));
        $this->assertSame(' first="value1" three="value3"', $traitAttributes->getAttributesString());
    }

    public function testAddAttribute_class()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttr(
            Attribute::create('class', 'value1')
        )->getAttr('class')->add('value2');
        $this->assertSame(['value1', 'value2'], $traitAttributes->getClassesList());
        $this->assertSame('value1 value2', $traitAttributes->getAttr('class')->getValueString());

        $traitAttributes->addClasses(
            [
                1,
                2
            ]
        );

        $this->assertSame('value1 value2 1 2', $traitAttributes->getAttr('class')->getValueString());
    }

    public function testAddAttributeArrays()
    {
        /** @var Attributes $traitAttributes */
        $traitAttributes = $this->getMockForTrait(Attributes::class);
        $traitAttributes->setAttrs(Attribute::createFromArray(['first' => 'value1', 'second' => 'value2']));
        $this->assertSame('value1', $traitAttributes->getAttr('first')->getValueString());
        $this->assertSame('value2', $traitAttributes->getAttr('second')->getValueString());

        $traitAttributes->setAttrs(Attribute::createFromArray(['value_withoutkey', 'second' => 'value2']));
        $this->assertSame('value_withoutkey', $traitAttributes->getAttr('value_withoutkey')->__toString());

        $traitAttributes->addClass('class1');
        $this->assertSame('class1', $traitAttributes->getAttr('class')->getValueString());

        $traitAttributes->addClass('class2 class3');
     //   $trait->addClass('class1');

        $this->assertSame(
            'class1 class2 class3',
            $traitAttributes->getAttr('class')->getValueString()
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
        $this->assertSame( 'class1 class3',
            $traitAttributes->getAttr('class')->getValueString()
        );


        $traitAttributes->setAttrs(Attribute::createFromArray(['value_withoutkey', 'second' => 'value2']), 'extra');

        $traitAttributes->addClass('class1', 'extra');
        $traitAttributes->removeClass('class1', 'extra');

        $this->assertSame([],$traitAttributes->getAttr('class', 'extra')->getValues());


        $this->assertStringContainsString(
            'first="value1" value_withoutkey second="value2" class="class1 class3',
            $traitAttributes->getAttributesString()
        );
        $this->assertStringContainsString('value_withoutkey second="value2"', $traitAttributes->getAttributesString('extra'));

        $this->assertSame(null, $traitAttributes->getAttr('value', 'extra2'));
        $this->assertSame('', $traitAttributes->getAttributesString('extra3'));
    }


}
