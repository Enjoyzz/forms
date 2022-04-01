<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;


class ElementTest
{
    use Reflection;

    /**
     *
     * @var  Enjoys\Forms\Forms $form
     */
    protected $obj;

    protected function setUp(): void
    {
        $this->obj = new Form();
        if (isset($this->obj->getElements()[Form::_TOKEN_SUBMIT_])) {
            $this->obj->removeElement($this->obj->getElements()[Form::_TOKEN_SUBMIT_]);
        }

        if (isset($this->obj->getElements()[Form::_TOKEN_CSRF_])) {
            $this->obj->removeElement($this->obj->getElements()[Form::_TOKEN_CSRF_]);
        }
    }

    protected function tearDown(): void
    {
        $this->obj = null;
    }

    public function test_setName_1_0()
    {
        $element = new Text('Foo');
        $this->assertEquals('Foo', $element->getName());
    }

    public function test_setId_1_0()
    {
        $element = new Text('Foo');
        $this->assertSame('Foo', $element->getAttr('id')->getValueString());
        $element->setAttr(Attribute::create('id', 'Baz'));
        $this->assertSame('Baz', $element->getAttr('id')->getValueString());
    }

    public function test_getType_1_0()
    {
        $select = $this->obj->select('Foo');
        $this->assertEquals('option', $select->getType());
        $text = $this->obj->text('Bar');
        $this->assertEquals('text', $text->getType());
    }

    public function test_setLabel_1_0()
    {
        $element = new Text('Foo', 'Bar');
        $this->assertEquals('Bar', $element->getLabel());
        $element->setLabel('Baz');
        $this->assertEquals('Baz', $element->getLabel());
    }

    public function test_setFormDefaults_1_1()
    {
        $this->obj->setDefaults([
            'Foo' => [
                'first_string',
                'second_string'
            ]
        ]);
//        $this->obj->setOptions([
//            'defaults' => [
//                'Foo' => [
//                    'first_string', 'second_string'
//                ]
//            ]
//        ]);
        $element = $this->obj->text('Foo[]', 'Bar');
        $this->assertEquals('first_string', $element->getAttr('value')->getValueString());
    }

    public function test_addRule_1_0()
    {
        $element = $this->obj->text('Foo', 'Bar')->addRule(Rules::REQUIRED, 'will be required');
        $this->assertEquals('will be required', $element->getRules()[0]->getMessage());
    }

    public function test_addRule_invalid()
    {
        $this->expectException(ExceptionRule::class);
        $element = $this->obj->text('Foo', 'Bar')->addRule('invalid');
    }

    public function test_setRuleMessage_1_0()
    {
        $element = new Text('Foo', 'Bar');
        $element->setRuleError('rule message error');
        $this->assertEquals('rule message error', $element->getRuleErrorMessage());
        $this->assertEquals(true, $element->isRuleError());
    }

    public function test_isrequired()
    {
        $element = new Text('Foo', 'Bar');
        $this->assertEquals(false, $element->isRequired());
        $element->addRule(Rules::REQUIRED);
        $this->assertEquals(true, $element->isRequired());
    }

    public function test_baseHtml()
    {
        $element = new Text('text');
        $element->getAttributeCollection()
            ->remove('id')
            ->remove('name')
        ;
        $this->assertEquals('<input type="text">', $element->baseHtml());
    }
}
