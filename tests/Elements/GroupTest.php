<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Group;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Form;
use Tests\Enjoys\Forms\_TestCase;
use Webmozart\Assert\InvalidArgumentException;


class GroupTest extends _TestCase
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
        $this->expectException(InvalidArgumentException::class);
        $g = new Group('group1');
        $g->invalid();
    }

    public function testSetDefaultsArraysForGroupSelect()
    {
//        $request = new ServerRequestWrapper(
//            new ServerRequest(parsedBody: [
//                'foo1' => 'a',
//                'foo2' => [
//                    1 => [
//                        'test' => [
//                            2 => 'b'
//                        ]
//                    ]
//                ],
//                'foo3' => [
//                    1 => 'c'
//                ],
//            ], method: 'post')
//        );
//
//        $tokenSubmitMock = $this->getMockBuilder(TockenSubmit::class)
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//        $tokenSubmitMock->expects($this->once())->method('getSubmitted')->will($this->returnValue(true));
//
//        $form = $this->getMockBuilder(Form::class)
//            ->disableOriginalConstructor()
//            ->addMethods(['tockenSubmit'])
//            ->getMock()
//        ;
//
//        $form->expects($this->once())->method('tockenSubmit')->will(
//            $this->returnCallback(
//                function () use ($tokenSubmitMock) {
//                    return $tokenSubmitMock;
//                }
//            )
//        );

        $form = new Form();

        $form->setDefaults([
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
                ]
            ]
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

        $this->assertEquals('POST', $form->getMethod());
        $this->assertNotNull($element1->getElements()[1]->getAttr('selected'));
        $this->assertNotNull($element2->getElements()[2]->getAttr('selected'));
        $this->assertSame('c', $element3->getAttr('value')->getValueString());
    }

    public function testAddInvalidElement()
    {
        $this->expectException(InvalidArgumentException::class);
        $el = new Group();
        $el->add([
            'not element'
        ]);
    }
}
