<?php

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Traits\Attributes;
use PHPUnit\Framework\TestCase;

/**
 * Class AttributeTest
 * @package Tests\Enjoys\Forms\Traits
 */
class AttributeTest extends TestCase
{

    /**
     *
     * @var  Enjoys\Forms\Forms $obj
     */
//    protected $obj;
//
//    protected function setUp(): void
//    {
//        $this->obj = new Form();
//    }
//
//    protected function tearDown(): void
//    {
//        $this->obj = null;
//    }

    /**
     * @dataProvider data
     */
    public function test_attributes($attributes, $expect)
    {
        $trait = $this->getMockForTrait(Attributes::class);
        $trait->setAttributes($attributes);
        $this->assertEquals($expect, $trait->getAttributesString());
    }

    public function data()
    {
        return [
            [['test'], ' test'],
            [[54 => 'test'], ' test'],
            [[' 0' => 'test'], ' 0="test"'],
            [['test', 'id' => 'test'], ' test id="test"'],
        ];
    }

    public function testAddAttribute_arrays()
    {
        $trait = $this->getMockForTrait(Attributes::class);
        $trait->setAttributes(['first' => 'value1', 'second' => 'value2']);
        $this->assertSame('value1', $trait->getAttribute('first'));
        $this->assertSame('value2', $trait->getAttribute('second'));

        $trait->setAttributes(['value_withoutkey', 'second' => 'value2']);
        $this->assertEquals(null, $trait->getAttribute('value_withoutkey'));

        $trait->addClass('class1');
        $this->assertEquals(['class1'], $trait->getAttribute('class'));

        $trait->addClass('class2 class3');
        $trait->addClass('class1');

        $this->assertEquals(
            [
                'class1',
                'class2',
                'class3',
            ],
            $trait->getAttribute('class')
        );


        $trait->removeClass('class2');
        $this->assertEquals(
            [
                'class1',
                'class3'
            ],
            array_values($trait->getAttribute('class'))
        );


        $trait->setAttributes(['value_withoutkey', 'second' => 'value2'], 'extra');
        $trait->removeClass('class1', 'extra');

        $trait->addClass('class1', 'extra');
        $trait->removeClass('class1', 'extra');

        $this->assertEquals([], $trait->getAttribute('class', 'extra'));

        $this->assertEquals(null, $trait->getAttribute('value_withoutkey'));

        $this->assertStringContainsString(
            'first="value1" second="value2" value_withoutkey class="class1 class3',
            $trait->getAttributesString()
        );
        $this->assertStringContainsString('value_withoutkey second="value2"', $trait->getAttributesString('extra'));

        $this->assertEquals(false, $trait->getAttribute('value', 'extra2'));
        $this->assertEquals('', $trait->getAttributesString('extra3'));

        $trait->setAttributes(
            [
                'first' => [
                    'value1',
                    'value2'
                ],
                'second' => 'value3'
            ]
        );
        $this->assertSame('value1 value2', $trait->getAttribute('first'));

        $trait->setAttributes(['first' => 'value1'], 'extra4')->setAttributes(['first' => 'value2'], 'extra4');
        $this->assertSame('value2', $trait->getAttribute('first', 'extra4'));

        $trait->setAttributes(
            [
                'class' => [
                    'value1',
                    'value2'
                ]
            ],
            'extra5'
        );
        $this->assertSame(['value1 value2'], $trait->getAttribute('class', 'extra5'));
    }

    public function testAddAttribute_class()
    {
        $trait = $this->getMockForTrait(Attributes::class);
        $trait->setAttribute('class', 'value1')->setAttribute('class', 'value2');
        $this->assertSame(['value1', 'value2'], $trait->getAttribute('class'));

        $trait->addClass(
            [
                1,
                2
            ]
        );

        $this->assertSame(['value1', 'value2', '1', '2'], $trait->getAttribute('class'));
    }

    public function testGetAttributesString()
    {
        $trait = $this->getMockForTrait(Attributes::class);
        $trait->setAttribute('first', 'value1');
        $trait->setAttribute('second', []);
        $trait->setAttribute('three', 'value3');
        $this->assertSame(' first="value1" three="value3"', $trait->getAttributesString());
    }


}
