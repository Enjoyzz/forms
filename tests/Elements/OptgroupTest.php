<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;


class OptgroupTest
{

    public function test_baseHtml()
    {
        $form = new \Enjoys\Forms\Form();
        $og = $form->optgroup(
                'foo',
                'parentname'
        );
        $this->assertEquals(null, $og->baseHtml());
    }
    
    public function test_setdefaults()
    {
        $this->markTestSkipped('Изменить тест');
        $form = new \Enjoys\Forms\Form();
        $form->setDefaults([
            'select1' => [
                1,3,'b','c'
            ]
        ]);
        $select = $form->select('select1')
                ->setOptgroup('numbers', [1,2,3], [], true)
                ->setOptgroup('alpha', ['a', 'b', 'c'], [], true)
                ->setMultiple()
                ;
        $this->assertNull($select->getElements()[0]->getElements()[0]->getAttr('selected')->getValueString());
        $this->assertFalse($select->getElements()[0]->getElements()[1]->getAttr('selected')->getValueString());
        $this->assertNull($select->getElements()[0]->getElements()[2]->getAttr('selected')->getValueString());
        $this->assertFalse($select->getElements()[1]->getElements()[0]->getAttr('selected')->getValueString());
        $this->assertNull($select->getElements()[1]->getElements()[1]->getAttr('selected')->getValueString());
        $this->assertNull($select->getElements()[1]->getElements()[2]->getAttr('selected')->getValueString());
    }
}
