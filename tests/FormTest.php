<?php

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\DefaultsHandler;
use Enjoys\Forms\Elements\Image;
use Enjoys\Forms\Elements\Select;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\ServerRequestWrapper;
use Enjoys\Session\Session;
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
        $this->assertSame('action="test"', $form->getAttribute('action')->__toString());
        $form->setAction(null);
        $this->assertNull($form->getAction());
        $this->assertSame('', $form->getAttribute('action')->__toString());
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
        $this->assertSame($expected, $form->getAttribute('method')->getValueString());
    }

    public function testSetIdDuringInit()
    {
        $form = new Form(id: 'my-id');
        $this->assertSame('my-id', $form->getAttribute('id')->getValueString());
        $this->assertSame('my-id', $form->getId());
    }


    public function testSetIdAfterInit()
    {
        $form = new Form();
        $this->assertNull($form->getId());
        $form->setId('my-id');
        $this->assertSame('my-id', $form->getAttribute('id')->getValueString());
        $this->assertSame('my-id', $form->getId());
    }

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
        $this->assertInstanceOf(Image::class, $form->getElement('foo'));
        $form->text('foo');
        $this->assertInstanceOf(Text::class, $form->getElement('foo'));
    }

    public function testRemoveElement()
    {
        $form = new Form();
        $el = $form->text('foo');
        $this->assertNotNull($form->getElement('foo'));
        $form->removeElement($el);
        $this->assertNull($form->getElement('foo'));
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

    public function testSetDefaultsIfSubmittedMethodGet(): void
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'foo' => 'baz',
            ], method: 'get')
        );
        $form = new Form('get', request: $request);

        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setValue($form, true);

        $form->setDefaults([
            'foo' => 'bar'
        ]);

        $element = $form->text('foo');
        $this->assertSame('baz', $element->getAttribute('value')->getValueString());
    }

    public function testSetDefaultsIfSubmittedMethodPost(): void
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(parsedBody: [
                'foo' => 'baz',
            ], method: 'Post')
        );
        $form = new Form('Post', request: $request);

        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setValue($form, true);

        $form->setDefaults([
            'foo' => 'bar'
        ]);

        $element = $form->text('foo');
        $this->assertSame('baz', $element->getAttribute('value')->getValueString());
    }

    /**
     * @throws \ReflectionException
     */
    public function testSetDefaultsIfSubmittedReal(): void
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'foo' => 'baz',
                Form::_TOKEN_SUBMIT_ => '57416ee9a4789178e6cf4de6bc797ebd',
                Form::_TOKEN_CSRF_ => 'csrf-token-stub',
            ])
        );
        $defaultsHandler = new DefaultsHandler([
            'foo' => 'bar'
        ]);

        $this->assertSame(['foo' => 'bar'], $defaultsHandler->getDefaults());

        $form = new Form('get', request: $request, defaultsHandler: $defaultsHandler);

        $element = $form->text('foo');

        $this->assertSame('baz', $element->getAttribute('value')->getValueString());
        $this->assertSame(['foo' => 'baz'], $form->getDefaultsHandler()->getDefaults());
    }

    public function testSetDefaults()
    {
        $form = new Form();
        $form->setDefaults([
            'foo' => 'bar'
        ]);
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttribute('value')->getValueString());
    }

    public function testSetDefaultsWithInitialization()
    {
        $form = new Form(
            defaultsHandler: new DefaultsHandler([
                'foo' => 'bar'
            ])
        );
        $element = $form->text('foo');
        $this->assertSame('bar', $element->getAttribute('value')->getValueString());
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
        $this->assertSame('bar', $element->getAttribute('value')->getValueString());
    }

    public function testSetDefaultsClosureAfterSubmit()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(parsedBody: [
                'foo' => 'baz'
            ])
        );
        $form = new Form(request: $request);

        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setAccessible(true);
        $submitted->setValue($form, true);

        $form->setDefaults(function () {
            return [
                'foo' => 'bar'
            ];
        });
        $element = $form->text('foo');
        $this->assertSame('baz', $element->getAttribute('value')->getValueString());
    }

    public function testSetDefaultsInvalidClosureData()
    {
        $this->expectException(InvalidArgumentException::class);
        $form = new Form();
        $form->setDefaults(function () {
            return 'string';
        });
    }

    public function testSetAndGetDefaultsHandler()
    {
        $defaultsHandler = new DefaultsHandler([1, 2, 3]);
        $form = new Form(defaultsHandler: $defaultsHandler);
        $this->assertEquals($defaultsHandler, $form->getDefaultsHandler());
    }

    /**
     * @throws \ReflectionException
     */
    public function testSetAndGetSession()
    {
        $session = clone new Session();
        $form = new Form(session: $session);
        $property = $this->getPrivateProperty(Form::class, 'session');
        $this->assertSame($session, $property->getValue($form));
    }

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


    /**
     * @throws \ReflectionException
     * @throws ExceptionRule
     */
    public function testSkipValidate(): void
    {
        $form = new Form();
        $property = $this->getPrivateProperty(Form::class, 'submitted');
        $property->setValue($form, true);
        $form->text('foo')->addRule(Rules::REQUIRED);
        $this->assertTrue($form->isSubmitted(false));
    }

    /**
     * @throws \ReflectionException
     * @throws ExceptionRule
     */
    public function testValidateTrueAfterSubmit(): void
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

    public function testAddElementAfter()
    {
        $form = new Form(method: 'get');
        $form->text('elem1');
        $form->text('elem2');
        $form->text('elem3');
        $elemInjected = new Text('injected');
        $form->addElement($elemInjected, after: 'elem1');
        $this->assertSame([
            '_token_submit',
            'elem1',
            'injected',
            'elem2',
            'elem3',
        ],
            array_map(function ($item) {
                return $item->getName();
            }, $form->getElements()));
    }

    public function testAddElementBefore()
    {
        $form = new Form(method: 'get');
        $form->text('elem1');
        $form->text('elem2');
        $form->text('elem3');
        $elemInjected = new Text('injected');
        $form->addElement($elemInjected, before: 'elem2');
        $this->assertSame([
            '_token_submit',
            'elem1',
            'injected',
            'elem2',
            'elem3',
        ],
            array_map(function ($item) {
                return $item->getName();
            }, $form->getElements()));
    }


}
