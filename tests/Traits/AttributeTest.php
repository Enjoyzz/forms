<?php

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Text;

class AttributeTest
{


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

//
//    /**
//     *
//     * @var  Enjoys\Forms\Forms $obj
//     */
////    protected $obj;
////
////    protected function setUp(): void
////    {
////        $this->obj = new Form();
////    }
////
////    protected function tearDown(): void
////    {
////        $this->obj = null;
////    }
//
//    /**
//     * @dataProvider data
//     */
//    public function test_attributes($attributes, $expect)
//    {
//        $trait = $this->getMockForTrait(Attributes::class);
//        $trait->setAttrs(Attribute::createFromArray($attributes));
//        $this->assertEquals($expect, $trait->getAttributesString());
//    }
//
//    public function data()
//    {
//        return [
//            [['test'], ' test'],
//            [[54 => 'test'], ' test'],
//            [[' 0' => 'test'], ' 0="test"'],
//            [['test', 'id' => 'test'], ' test id="test"'],
//            [['test' => function(){return null;}], ' test'],
//            [['test' => function(){return 'test';}], ' test="test"'],
//        ];
//    }
//
//    public function testAddAttribute_arrays()
//    {
//        $trait = $this->getMockForTrait(Attributes::class);
//        $trait->setAttrs(['first' => 'value1', 'second' => 'value2']);
//        $this->assertSame('value1', $trait->getAttr('first'));
//        $this->assertSame('value2', $trait->getAttr('second'));
//
//        $trait->setAttributes(['value_withoutkey', 'second' => 'value2']);
//        $this->assertEquals(null, $trait->getAttr('value_withoutkey'));
//
//        $trait->addClass('class1');
//        $this->assertEquals('class1', $trait->getAttr('class'));
//
//        $trait->addClass('class2 class3');
//     //   $trait->addClass('class1');
//
//        $this->assertEquals(
//            'class1 class2 class3',
//            $trait->getAttr('class')
//        );
//
//        $this->assertEquals(
//            [
//                'class1',
//                'class2',
//                'class3',
//            ],
//            $trait->getClassesList()
//        );
//
//
//        $trait->removeClass('class2');
//        $this->assertEquals( 'class1 class3',
//            $trait->getAttr('class')
//        );
//
//
//        $trait->setAttributes(['value_withoutkey', 'second' => 'value2'], 'extra');
//        $trait->removeClass('class1', 'extra');
//
//        $trait->addClass('class1', 'extra');
//        $trait->removeClass('class1', 'extra');
//
//        $this->assertEquals('', $trait->getAttr('class', 'extra'));
//
//        $this->assertEquals(null, $trait->getAttr('value_withoutkey'));
//
//        $this->assertStringContainsString(
//            'first="value1" second="value2" value_withoutkey class="class1 class3',
//            $trait->getAttributesString()
//        );
//        $this->assertStringContainsString('value_withoutkey second="value2"', $trait->getAttributesString('extra'));
//
//        $this->assertEquals(false, $trait->getAttr('value', 'extra2'));
//        $this->assertEquals('', $trait->getAttributesString('extra3'));
//
//        $trait->setAttributes(
//            [
//                'first' => [
//                    'value1',
//                    'value2'
//                ],
//                'second' => 'value3'
//            ]
//        );
//        $this->assertSame('value1 value2', $trait->getAttr('first'));
//
//        $trait->setAttributes(['first' => 'value1'], 'extra4')->getAttrs(['first' => 'value2'], 'extra4');
//        $this->assertSame('value2', $trait->getAttr('first', 'extra4'));
//
//        $trait->setAttributes(
//            [
//                'class' => [
//                    'value1',
//                    'value2'
//                ]
//            ],
//            'extra5'
//        );
//        $this->assertSame('value1 value2', $trait->getAttr('class', 'extra5'));
//    }
//
//    public function testAddAttribute_class()
//    {
//        $trait = $this->getMockForTrait(Attributes::class);
//        $trait->setAttribute('class', 'value1')->setAttribute('class', 'value2');
//        $this->assertSame(['value1', 'value2'], $trait->getClassesList());
//        $this->assertSame('value1 value2', $trait->getAttr('class'));
//
//        $trait->addClasses(
//            [
//                1,
//                2
//            ]
//        );
//
//        $this->assertSame('value1 value2 1 2', $trait->getAttr('class'));
//    }
//
//    public function testGetAttributesString()
//    {
//        $trait = $this->getMockForTrait(Attributes::class);
//        $trait->setAttribute('first', 'value1');
//        $trait->setAttribute('three', 'value3');
//        $this->assertSame(' first="value1" three="value3"', $trait->getAttributesString());
//    }
//

}
