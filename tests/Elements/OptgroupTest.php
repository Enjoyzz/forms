<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Optgroup;
use Enjoys\Forms\Elements\Select;
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
                1,
                3,
                'b',
                'c'
            ]
        ]);
        $select = $form->select('select1')
            ->setOptgroup('numbers', [1, 2, 3], [], true)
            ->setOptgroup('alpha', ['a', 'b', 'c'], [], true)
            ->setMultiple();
        $this->assertNotNull($select->getElements()[0]->getElements()[0]->getAttribute('selected'));
        $this->assertNull($select->getElements()[0]->getElements()[1]->getAttribute('selected'));
        $this->assertNotNull($select->getElements()[0]->getElements()[2]->getAttribute('selected'));
        $this->assertNull($select->getElements()[1]->getElements()[0]->getAttribute('selected'));
        $this->assertNotNull($select->getElements()[1]->getElements()[1]->getAttribute('selected'));
        $this->assertNotNull($select->getElements()[1]->getElements()[2]->getAttribute('selected'));
    }

    public function testSetDefaultsNoMultiple()
    {
        $form = new Form();
        $form->setDefaults([
            'select1' => 2
        ]);
        $select = $form->select('select1')
            ->setOptgroup('numbers', ['opt1', 'opt2', 'opt3'])
            ->setOptgroup('alpha', ['opt4', 'opt5', 'opt6']);

        $this->assertNotNull($select->getElements()[0]->getElements()[2]->getAttribute('selected'));
        $this->assertSame('opt3', $select->getElements()[0]->getElements()[2]->getLabel());
    }

    public function testSetDefaultsNoMultipleWhenNameIsArray()
    {
        $form = new Form();
        $form->setDefaults([
            's' => [
                's1' => [
                    's2' => '500'
                ]
            ]
        ]);

       $form->addElement((new Select('s[s1][s2]'))
            ->setForm($form)
            ->fill(['null'])
            ->setOptgroup('numbers', [300 => 'opt1', 500 => 'opt2', 800 => 'opt3'])
            ->setOptgroup('alpha', [350 => 'opt4', 548 => 'opt5', 795 => 'opt6']));


        $this->assertNotNull(
            $form->getElement('s[s1][s2]')->getElements()[1]->getElements()[1]->getAttribute('selected')
        );
        $this->assertSame('opt2', $form->getElement('s[s1][s2]')->getElements()[1]->getElements()[1]->getLabel());
    }
}
