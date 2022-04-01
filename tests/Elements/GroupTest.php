<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Group;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Form;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;


class GroupTest
{

    public function test_init_group()
    {
        $g = new Group('group1');
        $g->setForm(new Form());
        $g->add(
            [
                new Text('foo', 'bar')
            ]
        );
        $this->assertInstanceOf('\Enjoys\Forms\Element', $g->getElements()['foo']);
    }

    public function test_invalid_element()
    {
        $this->expectException('\Enjoys\Forms\Exception\ExceptionElement');
        $g = new Group('group1');
        $g->invalid();
    }

    public function test_setDefaultsArraysForGroupSelect()
    {
        $this->markTestSkipped('Проверить тест');
        $request = new ServerRequestWrapper(
            ServerRequestCreator::createFromGlobals(
                ['REQUEST_METHOD' => 'POST'],
                null,
                null,
                null,
                [
                    'foo1' => 'a',
                    'foo2' => [
                        1 => [
                            'test' => [
                                2 => 'b'
                            ]
                        ]
                    ],
                    'foo3' => [
                        1 => 'c'
                    ],
                ]
            )
        );

        $tockenSubmitMock = $this->getMockBuilder(TockenSubmit::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tockenSubmitMock->expects($this->once())->method('getSubmitted')->will($this->returnValue(true));

        $form = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->addMethods(['tockenSubmit'])
            ->getMock();

        $form->expects($this->once())->method('tockenSubmit')->will(
            $this->returnCallback(
                function () use ($tockenSubmitMock) {
                    return $tockenSubmitMock;
                }
            )
        );

        $form->__construct(
            [
                'method' => 'post'
            ],
            $request
        );


        $fill = [
            'false',
            'a',
            'b',
            'c',
            'd'
        ];

        $element1 = (new Select('foo1'))->fill($fill, true);
        $element2 = (new Select('foo2[1][test][2]'))->fill($fill, true);
        $element3 = (new Text('foo3[1]'));

        $form->group('group')->add(
            [
                $element1,
                $element2,
                $element3,
            ]
        );

        $this->assertEquals('POST', $form->getRequestWrapper()->getRequest()->getMethod());

        $this->assertNull($element1->getElements()[1]->getAttr('selected'));
        $this->assertNull($element2->getElements()[2]->getAttr('selected'));
        $this->assertSame('c', $element3->getAttr('value')->getValueString());
    }
}
