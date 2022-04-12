<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;


use Enjoys\Forms\Elements\Optgroup;
use Enjoys\Forms\Form;
use Tests\Enjoys\Forms\_TestCase;

class OptgroupTest extends _TestCase
{

    public function testBaseHtml()
    {
        $form = new Form();
        $og = $form->optgroup(
                'foo',
                'parentname'
        );
        $this->assertEquals(null, $og->baseHtml());
    }

    public function testGetName()
    {
        $el = new Optgroup('Title', 'ParentName');
        $this->assertSame('ParentName', $el->getName());
    }

    public function testGetLabel()
    {
        $el = new Optgroup('Title', 'ParentName');
        $this->assertSame('Title', $el->getLabel());
    }
    
    public function testSetDefaults()
    {
        $form = new Form();
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
        $this->assertNotNull($select->getElements()[0]->getElements()[0]->getAttr('selected'));
        $this->assertNull($select->getElements()[0]->getElements()[1]->getAttr('selected'));
        $this->assertNotNull($select->getElements()[0]->getElements()[2]->getAttr('selected'));
        $this->assertNull($select->getElements()[1]->getElements()[0]->getAttr('selected'));
        $this->assertNotNull($select->getElements()[1]->getElements()[1]->getAttr('selected'));
        $this->assertNotNull($select->getElements()[1]->getElements()[2]->getAttr('selected'));
    }
}
