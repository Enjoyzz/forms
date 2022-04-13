<?php

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\DefaultsHandler;
use Enjoys\Forms\Elements\Image;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\ServerRequestWrapper;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use Webmozart\Assert\InvalidArgumentException;


class FormTest extends _TestCase
{
    use Reflection;

    public function dataForTestInitFormWithParams()
    {
        return [
            [null, ['POST', null]],
            [['get', null], ['GET', null]],
            [['invalid', null], ['POST', null]],
            [['post', '/handler.php'], ['POST', '/handler.php']],
        ];
    }

    /**
     * @dataProvider dataForTestInitFormWithParams
     */
    public function testInitFormWithParams($params, $expect)
    {
        $form = $params === null ? new Form() : new Form($params[0], $params[1]);
        $this->assertSame($expect[0], $form->getMethod());
        $this->assertSame($expect[1], $form->getAction());
    }

    public function testSetAction()
    {
        $form = new Form();
        $form->setAction('test');
        $this->assertSame('test', $form->getAction());
        $form->setAction();
        $this->assertNull($form->getAction());
    }


    public function dataForTestSetMethod()
    {
        return [
            ['get', 'GET'],
            ['Get', 'GET'],
            ['GeT', 'GET'],
            ['post', 'POST'],
            ['pOSt', 'POST'],
            ['something', 'POST']
        ];
    }

    /**
     * @dataProvider dataForTestSetMethod
     */
    public function testSetMethod($method, $expected)
    {
        $form = new Form();
        $form->setMethod($method);
        $this->assertSame($expected, $form->getMethod());
    }

//    public function testSetAttrName()
//    {
//        $form = new Form();
//        $form->setAttr(AttributeFactory::create('name', 'test_form'));
//        $this->assertEquals('test_form', $form->getAttr('name')->getValueString());
//    }


    public function testAddElement()
    {
        $form = new Form();
        $element = new Text('foo');
        $form->addElement($element);
        //incl CSRF token, because method POST
        $this->assertCount(3, $form->getElements());
    }

    public function testCountElements()
    {
        $form = new Form();
        $form->text('foo');
        $form->hidden('bar');
        $form->password('baz');

        //incl Submit token
        //incl CSRF token, because method POST
        $this->assertCount(5, $form->getElements());
    }

    public function testAddElementRewrite()
    {
        $form = new Form();
        $form->image('foo');
        $this->assertInstanceOf(Image::class, $form->getElements()['foo']);
        $form->text('foo');
        $this->assertInstanceOf(Text::class, $form->getElements()['foo']);
    }

    public function testRemoveElement()
    {
        $form = new Form();
        $el = $form->text('foo');
        $this->assertSame(true, isset($form->getElements()['foo']));
        $form->removeElement($el);
        $this->assertSame(true, !isset($form->getElements()['foo']));
    }

    public function testGetFormDefaults()
    {
        $form = new Form();

        $this->assertInstanceOf(DefaultsHandler::class, $form->getDefaultsHandler());

        $this->assertEquals([], $form->getDefaultsHandler()->getDefaults());
        $form->setDefaults([1]);
        $this->assertEquals([1], $form->getDefaultsHandler()->getDefaults());
    }


    public function testCallValid()
    {
        $form = new Form();
        $select = $form->select('select');
        $this->assertInstanceOf(Select::class, $select);
    }

    public function testCallInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $form = new Form();
        $form->invalid();
    }

    public function test_remove_elements()
    {
        //$this->markTestIncomplete();
        $form = new Form();
        $form->text('foo');
        $form->hidden('bar');
        $form->removeElement($form->getElement(Form::_TOKEN_SUBMIT_));
        $form->removeElement($form->getElement(Form::_TOKEN_CSRF_));
        $form->removeElement($form->getElement('foo'));
        $form->removeElement($form->getElement('notisset'));

        $this->assertCount(1, $form->getElements());
    }

    public function testSetDefaults()
    {
        $form = new Form();
        $form->setDefaults([
            'foo' => 'bar'
        ]);
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttr('value')->getValueString());
    }

    public function testSetDefaultsClosure()
    {
        $form = new Form();
        $form->setDefaults(function () {
            return [
                'foo' => 'bar'
            ];
        });
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttr('value')->getValueString());
    }

    public function testSetDefaultsInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);
        $form = new Form();
        $form->setDefaults('value');
    }

//
//    public function test_setDefaults_1_1()
//    {
//        $property = $this->getPrivateProperty(Form::class, 'formSubmitted');
//
//        $form = new Form(
//            [], new ServerRequestWrapper(
//            ServerRequestCreator::createFromGlobals(null, null, null, [
//                'foo' => 'zed',
//                'bar' => true,
//                Form::_TOKEN_SUBMIT_ => '~token~'
//            ])
//        )
//        );
//
//        $property->setValue($form, true);
//
//        $form->setDefaults([
//            'foo' => 'bar',
//        ]);
//
//        $element = $form->text('foo');
//
//        $this->assertSame('zed', $element->getAttr('value')->getValueString());
//        $this->assertFalse($form->getDefaultsHandler()->getValue('Form::_TOKEN_SUBMIT_'));
//        $this->assertTrue($form->getDefaultsHandler()->getValue('bar'));
//    }
//
//    public function test_setDefaults_1_2()
//    {
//        $request = new ServerRequestWrapper(
//            ServerRequestCreator::createFromGlobals(null, null, null, [
//                'foo' => 'zed',
//            ])
//        );
//
//        $tockenSubmitMock = $this->getMockBuilder(TockenSubmit::class)
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//        $tockenSubmitMock->expects($this->once())->method('getSubmitted')->will($this->returnValue(true));
//
//        $form = $this->getMockBuilder(Form::class)
//            ->disableOriginalConstructor()
//            ->addMethods(['tockenSubmit'])
//            ->getMock()
//        ;
//
//        $form->expects($this->once())->method('tockenSubmit')->will(
//            $this->returnCallback(function () use ($tockenSubmitMock) {
//                return $tockenSubmitMock;
//            })
//        );
//
//        $form->__construct([], $request);
//        $element = $form->text('foo');
//
//        $this->assertSame('GET', $form->getRequestWrapper()->getRequest()->getMethod());
//        $this->assertSame('zed', $element->getAttr('value')->getValueString());
//    }
//
//    public function test_setDefaults_1_2_2()
//    {
//        $request = new ServerRequestWrapper(
//            ServerRequestCreator::createFromGlobals(
//                ['REQUEST_METHOD' => 'POST'],
//                null,
//                null,
//                null,
//                [
//                    'foo' => 'zed'
//                ]
//            )
//        );
//
//        $tockenSubmitMock = $this->getMockBuilder(TockenSubmit::class)
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//        $tockenSubmitMock->expects($this->once())->method('getSubmitted')->will($this->returnValue(true));
//
//        $form = $this->getMockBuilder(Form::class)
//            ->disableOriginalConstructor()
//            ->addMethods(['tockenSubmit'])
//            ->getMock()
//        ;
//
//        $form->expects($this->once())->method('tockenSubmit')->will(
//            $this->returnCallback(function () use ($tockenSubmitMock) {
//                return $tockenSubmitMock;
//            })
//        );
//
//        $form->__construct([
//            'method' => 'post'
//        ], $request);
//        $element = $form->text('foo');
//
//        $this->assertEquals('POST', $form->getRequestWrapper()->getRequest()->getMethod());
//        $this->assertEquals('zed', $element->getAttr('value')->getValueString());
//    }
//
    public function testIsSubmitted()
    {
        $form = new Form();
        $this->assertFalse($form->isSubmitted());
    }

    public function testValidateFalseAfterSubmit()
    {
        $form = new Form();
        $form->removeElement($form->getElement(Form::_TOKEN_CSRF_));
        $property = $this->getPrivateProperty(Form::class, 'submitted');
        $property->setValue($form, true);
        $form->text('foo')->addRule(Rules::REQUIRED);
        $this->assertFalse($form->isSubmitted());
    }

    public function testSkipValidate()
    {
        $form = new Form();
        $property = $this->getPrivateProperty(Form::class, 'submitted');
        $property->setValue($form, true);
        $form->text('foo')->addRule(Rules::REQUIRED);
        $this->assertTrue($form->isSubmitted(false));
    }

    public function testValidateTrueAfterSubmit()
    {
        $form = new Form(
            request: new ServerRequestWrapper(
                new ServerRequest(
                    parsedBody: [
                        'foo' => 'test'
                    ],
                    method: 'POST'
                )
            )
        );
        $form->removeElement($form->getElement(Form::_TOKEN_CSRF_));
        $property = $this->getPrivateProperty(Form::class, 'submitted');
        $property->setValue($form, true);
        $form->text('foo')->addRule(Rules::REQUIRED);
        $this->assertTrue($form->isSubmitted());
    }

//    public function test_formCount_1_0()
//    {
//        $form1 = new Form();
//        $this->assertSame(1, $form1->getFormCounter());
//        unset($form1);
//    }
//
//    public function test_formCount_1_1()
//    {
//        $form1 = new Form();
//        $this->assertNotSame(2, $form1->getFormCounter());
//        unset($form1);
//    }
//
//    public function test_formCount_2_0()
//    {
//        $form1 = new Form();
//        $form1->file('myfile');
//
//        $this->assertSame(1, $form1->getFormCounter());
//
//        $form2 = new Form();
//        $form2->file('myfile');
//
//        $this->assertSame(2, $form1->getFormCounter());
//    }
//
//    public function test_formCount_2_1()
//    {
//        $form1 = new Form();
//        $this->assertNotSame(2, $form1->getFormCounter());
//        $form2 = new Form();
//        $this->assertNotSame(1, $form2->getFormCounter());
//    }
//
//    public function test_setDefaults_1_3()
//    {
//        $form1 = new Form();
//        $form1->setOption('defaults', ['foo' => 'bar']);
//        $form2 = new Form();
//        $this->assertEquals(['foo' => 'bar'], $form1->getDefaultsHandler()->getDefaults());
//        $this->assertEquals([], $form2->getDefaultsHandler()->getDefaults());
//    }
}
