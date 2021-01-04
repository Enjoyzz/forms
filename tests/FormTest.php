<?php

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\DefaultsHandler;
use Enjoys\Forms\Elements\Image;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Elements\TockenSubmit;
use Enjoys\Forms\Exception\ExceptionElement;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Http\ServerRequest;
use HttpSoft\ServerRequest\ServerRequestCreator;
use PHPUnit\Framework\TestCase;

/**
 * Class FormTest
 * @package Tests\Enjoys\Forms
 */
class FormTest extends TestCase
{
    use Reflection;

    public function test_init_form_1_0()
    {
        $form = new Form();
        $this->assertEquals('GET', $form->getMethod());
    }

    public function data_init_form_1_1()
    {
        return [
            ['get', 'GET'],
            ['Get', 'GET'],
            ['GeT', 'GET'],
            ['post', 'POST'],
            ['pOSt', 'POST'],
            ['something', 'GET']
        ];
    }

    /**
     * 
     * @dataProvider data_init_form_1_1
     */
    public function test_init_form_1_1($method, $expected)
    {
        $form = new Form([
            'method' => $method
        ]);
        $this->assertEquals($expected, $form->getMethod());
    }

    public function test_setName_1_0()
    {
        $form = new Form();
        $form->setOption('name', 'test_form');
        $this->assertEquals('test_form', $form->getOption('name'));
        $this->assertEquals('test_form', $form->getAttribute('name'));
        $form->setOption('name', null);
        $this->assertEquals(false, $form->getOption('name', false));
        $this->assertEquals(false, $form->getAttribute('name'));
    }

    public function test_setAction_1_0()
    {
        $form = new Form([
            'method' => 'post',
            'action' => 'test.php',
        ]);
        $this->assertEquals('test.php', $form->getOption('action'));
        $this->assertEquals('test.php', $form->getAttribute('action'));
        $form->setOption('action', 'foo.php');
        $this->assertEquals('foo.php', $form->getOption('action'));
        $this->assertEquals('foo.php', $form->getAttribute('action'));
        $form->setOption('action', null);
        $this->assertEquals(null, $form->getOption('action'));
        $this->assertEquals(false, $form->getAttribute('action'));
    }

    public function test_addElement_1_0()
    {

        $form = new Form();
        $element = new Text('foo');
        $form->addElement($element);
        $this->assertCount(2, $form->getElements());
    }

    public function test_count_elements()
    {
        $form = new Form();
        $form->text('foo');
        $form->hidden('bar');
        $form->password('baz');
        //+1 submit_tokene element
        $this->assertCount(4, $form->getElements());
    }

    public function test_addElement_rewrite()
    {

        $form = new Form();
        $form->image('foo');
        $this->assertEquals(true, $form->getElements()['foo'] instanceof Image);
        $form->text('foo');
        $this->assertEquals(true, $form->getElements()['foo'] instanceof Text);
    }

    public function test_removeElement_1_0()
    {

        $form = new Form();
        $form->text('foo');
        $this->assertEquals(true, isset($form->getElements()['foo']));
        $form->removeElement($form->getElements()['foo']);
        $this->assertEquals(true, !isset($form->getElements()['foo']));
    }

    public function test_getFormDefaults()
    {
        $form = new Form();
        //$form->setOption('defaults', ['foo' => 'bar']);
        $this->assertEquals(true, $form->getDefaultsHandler() instanceof DefaultsHandler);
        $this->assertEquals([], $form->getDefaultsHandler()->getDefaults());
        $form->setDefaults([1]);
        $this->assertEquals([1], $form->getDefaultsHandler()->getDefaults());
    }

    /**
     * 
     * @dataProvider dataForConstruct
     */
    public function testConstruct($method, $action, $expectedMethod, $expectedAction)
    {

        $this->form = new Form([
            'action' => $action,
            'method' => $method
        ]);
        $this->assertSame($expectedMethod, $this->form->getAttribute('method'));
        $this->assertSame($expectedAction, $this->form->getAttribute('action'));
    }

    public function dataForConstruct()
    {
        return [
            ['get', '/action.php', 'GET', '/action.php'],
            ['get', null, 'GET', false],
            [null, null, false, false],
            ['Post', null, 'POST', false],
        ];
    }

    public function test_call_valid()
    {
        $form = new Form();
        $select = $form->select('select');
        $this->assertInstanceOf(Select::class, $select);
    }

    public function test_call_invalid()
    {
        $this->expectException(ExceptionElement::class);
        $form = new Form();
        $select = $form->invalid();
    }

    public function test_remove_elements()
    {
        //$this->markTestIncomplete();
        $form = new Form();
        $form->text('foo');
        $form->hidden('bar');
        $form->removeElement($form->getElement(Form::_TOKEN_SUBMIT_));
        $form->removeElement($form->getElement('foo'));
        $form->removeElement($form->getElement('notisset'));
        $this->assertCount(1, $form->getElements());
    }

    public function test_setDefaults_1_0()
    {
        $form = new Form();
        $form->setDefaults([
            'foo' => 'bar'
        ]);
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttribute('value'));
    }

    public function test_setDefaults_1_1()
    {
        $property = $this->getPrivateProperty(Form::class, 'formSubmitted');

        $form = new Form([], new ServerRequest(
                        ServerRequestCreator::createFromGlobals(null, null, null, [
                            'foo' => 'zed',
                            'bar' => true,
                            Form::_TOKEN_SUBMIT_ => '~token~'
                        ])
        ));

        $property->setValue($form, true);

        $form->setDefaults([
            'foo' => 'bar',
        ]);

        $element = $form->text('foo');

        $this->assertEquals('zed', $element->getAttribute('value'));
        $this->assertFalse($form->getDefaultsHandler()->getValue('Form::_TOKEN_SUBMIT_'));
        $this->assertTrue($form->getDefaultsHandler()->getValue('bar'));
    }

    public function test_setDefaults_1_2()
    {


        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(null, null, null, [
                    'foo' => 'zed',
                ])
        );

        $tockenSubmitMock = $this->getMockBuilder(TockenSubmit::class)
                ->disableOriginalConstructor()
                ->getMock();
        $tockenSubmitMock->expects($this->once())->method('getSubmitted')->will($this->returnValue(true));

        $form = $this->getMockBuilder(Form::class)
                ->disableOriginalConstructor()
                ->addMethods(['tockenSubmit'])
                ->getMock();

        $form->expects($this->once())->method('tockenSubmit')->will($this->returnCallback(function() use ($tockenSubmitMock) {
                    return $tockenSubmitMock;
                }));

        $form->__construct([], $request);
        $element = $form->text('foo');

        $this->assertEquals('GET', $form->getRequest()->getMethod());
        $this->assertEquals('zed', $element->getAttribute('value'));
    }

    public function test_setDefaults_1_2_2()
    {

        $request = new ServerRequest(
                ServerRequestCreator::createFromGlobals(
                        ['REQUEST_METHOD' => 'POST'],
                        null,
                        null,
                        null,
                        [
                          'foo' => 'zed'
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

        $form->expects($this->once())->method('tockenSubmit')->will($this->returnCallback(function() use ($tockenSubmitMock) {
                    return $tockenSubmitMock;
                }));

        $form->__construct([
            'method' => 'post'
                ], $request);
        $element = $form->text('foo');

        $this->assertEquals('POST', $form->getRequest()->getMethod());
        $this->assertEquals('zed', $element->getAttribute('value'));
    }

    public function test_isSubmitted_false()
    {
        $form = new Form();
        $property = $this->getPrivateProperty(Form::class, 'formSubmitted');
        $property->setValue($form, false);
        $this->assertFalse($form->isSubmitted());
    }

    public function test_validate_false_after_submit()
    {

        $form = new Form();
        $property = $this->getPrivateProperty(Form::class, 'formSubmitted');
        $property->setValue($form, true);
        $form->text('foo')->addRule(Rules::REQUIRED);
        $this->assertFalse($form->isSubmitted());
    }

    public function test_skip_validate()
    {

        $form = new Form();
        $property = $this->getPrivateProperty(Form::class, 'formSubmitted');
        $property->setValue($form, true);
        $form->text('foo')->addRule(Rules::REQUIRED);
        $this->assertTrue($form->isSubmitted(false));
    }

    public function test_validate_true_after_submit()
    {
        $form = new Form();
        $property = $this->getPrivateProperty(Form::class, 'formSubmitted');
        $property->setValue($form, true);
        $this->assertTrue($form->isSubmitted());
    }

    public function test_formCount_1_0()
    {
        $form1 = new Form();
        $this->assertSame(1, $form1->getFormCounter());
        unset($form1);
    }

    public function test_formCount_1_1()
    {
        $form1 = new Form();
        $this->assertNotSame(2, $form1->getFormCounter());
        unset($form1);
    }

    public function test_formCount_2_0()
    {
        $form1 = new Form();
        $form1->file('myfile');

        $this->assertSame(1, $form1->getFormCounter());

        $form2 = new Form();
        $form2->file('myfile');

        $this->assertSame(2, $form1->getFormCounter());
    }

    public function test_formCount_2_1()
    {
        $form1 = new Form();
        $this->assertNotSame(2, $form1->getFormCounter());
        $form2 = new Form();
        $this->assertNotSame(1, $form2->getFormCounter());
    }

    public function test_setDefaults_1_3()
    {
        $form1 = new Form();
        $form1->setOption('defaults', ['foo' => 'bar']);
        $form2 = new Form();
        $this->assertEquals(['foo' => 'bar'], $form1->getDefaultsHandler()->getDefaults());
        $this->assertEquals([], $form2->getDefaultsHandler()->getDefaults());
    }
}
