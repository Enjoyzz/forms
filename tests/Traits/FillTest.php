<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Elements\Option;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Traits\Fill;
use Enjoys\Traits\Reflection;
use PHPUnit\Framework\TestCase;


class FillTest extends TestCase
{

    use Reflection;

    public function testSetIndexKeyFill()
    {
        $select = new Select('select');
        $select->fill(
            [
                1,
                2,
                3
            ],
            true
        );
        $this->assertEquals('1', $select->getElements()[0]->getName());
        $this->assertEquals('2', $select->getElements()[1]->getName());
        $this->assertEquals('3', $select->getElements()[2]->getName());
        $select->fill(
            [
                1,
                2,
                3
            ],
            true
        );
        $this->assertEquals('1', $select->getElements()[3]->getName());
        $this->assertEquals('2', $select->getElements()[4]->getName());
        $this->assertEquals('3', $select->getElements()[5]->getName());

        $this->assertEquals('select', $select->getElements()[5]->getParentName());
    }

    public function testSetIndexKeyFillIntAsValue()
    {
        $select = new Select('select');
        $select->fill(
            [
                46 => 1,
                2,
                3
            ]
        );
        $this->assertEquals('46', $select->getElements()[0]->getAttr('value')->getValueString());
        $this->assertEquals('47', $select->getElements()[1]->getAttr('value')->getValueString());
        $this->assertEquals('48', $select->getElements()[2]->getAttr('value')->getValueString());
    }

    public function testClosureFill()
    {
        $class1 = new \stdClass();
        $class1->id = 52;
        $class1->name = '#52';

        $class2 = new \stdClass();
        $class2->id = 36;
        $class2->name = '#36';

        $data = [$class1, $class2];
        $select = new Select('select');
        $select->fill(function () use ($data) {
            $ret = [];
            foreach ($data as $item) {
                $ret[$item->id] = $item->name;
            }
            return $ret;
        });
        $this->assertEquals('52', $select->getElements()[0]->getAttr('value')->getValueString());
        $this->assertEquals('36', $select->getElements()[1]->getAttr('value')->getValueString());
    }

    public function testClosureInvalidReturn()
    {
        $this->expectException(\InvalidArgumentException::class);
        $select = new Select('select');
        $select->fill(function () {
            return 'invalid return';
        });
    }

    /**
     * @dataProvider elements
     */
    public function testFillWithAttributes($el, $name)
    {
        $class = '\Enjoys\Forms\Elements\\' . ucfirst($el);
        $element = new $class('foo');
        $element->fill([
            'test' => [
                'title1',
                [
                    'disabled'
                ]
            ],
            'foz' => [
                2,
                [
                    'id' => 'newfoz'
                ]
            ],
            'baz' => 3
        ]);
        $this->assertEquals('disabled', $element->getElements()[0]->getAttr('disabled')->__toString());
        $this->assertEquals(null, $element->getElements()[1]->getAttr('disabled')?->getValueString());
        $this->assertEquals('newfoz', $element->getElements()[1]->getAttr('id')->getValueString());
        $this->assertEquals($name, $element->getElements()[2]->getParentName());
    }

    public function elements()
    {
        return [
            ['select', 'foo'],
            ['radio', 'foo'],
            ['checkbox', 'foo[]'],
        ];
    }

    public function testSetDefaultsValue()
    {
        /** @var Fill $traitFill */
        $traitFill = $this->getMockForTrait(Fill::class);
        $traitFill->setDefaultValue([1]);
        $this->assertSame([1], $traitFill->getDefaultValue());

        $select = new Select('select');

        $defaultValue = $this->getPrivateProperty(Select::class, 'defaultValue');
        $defaultValue->setValue($select, [1]);

        $returnObject = $select->fill(
            [
                1,
                2,
                3
            ]
        );

        $this->assertSame('selected', $select->getElements()[1]->getAttr('selected')->__toString());
        $this->assertSame($returnObject, $select);
    }

    public function testAddElement()
    {
        $el = new Select('test');
        $el->addElement(new Option('1'))->addElement(new Option('2'));
        $this->assertCount(2, $el->getElements());
    }

    public function testAddElements()
    {
        $el = new Select('test');
        $returnObject = $el->addElements([new Option('1'), new Option('2')]);
        $this->assertCount(2, $el->getElements());
        $this->assertSame($returnObject, $el);
    }

}
