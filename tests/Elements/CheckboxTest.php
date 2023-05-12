<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;
use Tests\Enjoys\Forms\Reflection;

class CheckboxTest extends _TestCase
{
    use Reflection;

    public function testCheckRemoveAttributeName()
    {
        $el = new Checkbox('foo');
        $this->assertNull($el->getAttribute('name'));
    }


    public function testGetPrefixId()
    {
        $el = new Checkbox('foo');
        $this->assertSame('foo_', $el->getPrefixId());
    }


    public function test_title()
    {
        $obj = new Checkbox('name', 'title');
        $this->assertSame('title', $obj->getLabel());
    }

    public function test_title2()
    {
        $obj = new Checkbox('name', 'title');
        $obj->setLabel('title2');
        $this->assertSame('title2', $obj->getLabel());
    }

    public function test_name()
    {
        $obj = new Checkbox('name', 'title');
        $this->assertSame('name[]', $obj->getName());
    }


    private function filldata(): array
    {
        $obj = new Checkbox('name', 'title');
        $obj->setPrefixId('cb_');
        $obj->fill([
            'v1' => 't1',
            'v2' => [
                't2',
                [
                    'id' => 'i2',
                    'disabled'
                ]
            ]
        ]);
        return $obj->getElements();
    }

    public function test_fill()
    {
        $elements = $this->filldata();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('t1', $v1->getLabel());
    }

    public function test_fill2()
    {
        $elements = $this->filldata();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('v1[]', $v1->getName());
    }

    public function test_fill3()
    {
        $elements = $this->filldata();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('cb_v1', $v1->getAttribute('id')->getValueString());
    }

    public function test_fill4()
    {
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertSame('t2', $v2->getLabel());
    }

    public function test_fill5()
    {
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertSame('v2[]', $v2->getName());
    }

    public function test_fill6()
    {
        $elements = $this->filldata();

        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertSame('i2', $v2->getAttribute('id')->getValueString());
    }

    public function test_fill7()
    {
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertInstanceOf(Attribute::class, $v2->getAttribute('disabled'));
        $this->assertNotNull($v2->getAttribute('id'));
    }

    public function test_prefix()
    {
        $obj = new Checkbox('name', 'title');
        $obj->setPrefixId('prefix_');
        $obj->fill([
            'v1' => 't1'
        ]);

        $elements = $obj->getElements();
        /** @var Radio $v2 */
        $v1 = $elements[0];
        $this->assertSame('prefix_v1', $v1->getAttribute('id')->getValueString());
    }

    public function test_prefix2()
    {
        $obj = new Checkbox('name', 'title');
        $obj->setPrefixId('prefix_');
        $this->assertSame('name', $obj->getAttribute('id')->getValueString());

        $obj2 = new Checkbox('name', 'title');
        $this->assertSame('name', $obj2->getAttribute('id')->getValueString());
    }

    public function test_prefix3()
    {
        $obj = new Checkbox('name', 'title');
        $obj->setPrefixId('prefix_');
        $obj->fill([
            'v1' => [
                't1',
                ['id' => 'id1']
            ]
        ], true);

        $elements = $obj->getElements();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('id1', $v1->getAttribute('id')->getValueString());
    }

    public function test_count_checkbox_element()
    {
        $obj = new Checkbox('name', 'title');
        $obj->fill([
            1,
            2,
            3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_checkbox_element2()
    {
        $obj = new Checkbox('name', 'title');
        $obj->fill([
            1,
            1,
            3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_checkbox_element3()
    {
        $obj = new Checkbox('name', 'title');
        $obj->fill([1], true)->fill([1], true);

        $this->assertCount(2, $obj->getElements());
    }

    public function test_setDefault()
    {
        $form = new Form();
        $form->setDefaults([
            'name' => [
                1,
                2
            ]
        ]);
        $el = $form->checkbox('name', 'title')->fill([1, 2, 3], true);
        $elements = $el->getElements();
        $this->assertSame([1, 2], $el->getDefaultValue());
        $this->assertNotNull($elements[0]->getAttribute('checked'));
        $this->assertNotNull($elements[1]->getAttribute('checked'));
        $this->assertNull($elements[2]->getAttribute('checked'));
    }

    public function testSetDefaultIfSubmitted()
    {
        $request = new ServerRequest(parsedBody: [
            'name' => [3],
        ]);

        $form = new Form(request: $request);
        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setAccessible(true);
        $submitted->setValue($form, true);

        $form->setDefaults([
            'name' => [
                1,
                2
            ]
        ]);
        $el = $form->checkbox('name', 'title')->fill([1, 2, 3], true);
        $elements = $el->getElements();
        //  $this->assertSame([3], $el->getDefaultValue());
        $this->assertNull($elements[0]->getAttribute('checked'));
        $this->assertNull($elements[1]->getAttribute('checked'));
        $this->assertNotNull($elements[2]->getAttribute('checked'));
    }

    public function test_setDefault_simple()
    {
        $form = new Form();
        $form->setOption('defaults', [
            'name' => ['baaa']
        ]);
        $el = $form->checkbox('name', 'title')->fill([
            'val1' => 'Hello',
            'baaa' => 'Hello2',
            'aggg' => 5,
        ]);
        $elements = $el->getElements();
        $this->assertSame('baaa', $el->getDefaultValue());
        $this->assertNull($elements[0]->getAttribute('checked'));
        $this->assertNotNull($elements[1]->getAttribute('checked'));
        $this->assertNull($elements[2]->getAttribute('checked'));
    }

    public function testDefaultValue()
    {
        $form = new Form();
        $form->setDefaults([
            'name' => null
        ]);
        $el = $form->checkbox('name');
        $this->assertSame(false, $el->getDefaultValue());
    }


    public function test_basehtml()
    {
        $cb = new Checkbox('foo', 'bar');
        $this->assertStringContainsString(
            '<input type="checkbox" value="foo" id="foo" name=""><label for="foo">bar</label>',
            $cb->baseHtml()
        );
    }


    public function testElementsWithSameNames()
    {
        $form = new Form();
        $form->removeElement($form->getElement(Form::_TOKEN_SUBMIT_));
        $form->removeElement($form->getElement(Form::_TOKEN_CSRF_));

        $form->setDefaults([
            'foo' => [
                2,
                4
            ]
        ]);

        $element = $form->checkbox('foo', 'foo1')->fill([1, 2, 3], true);
        $element2 = $form->checkbox('foo', 'foo2')->fill([4, 5, 6], true);

        $this->assertCount(2, $form->getElements());

        $this->assertNull($element->getElements()[0]->getAttribute('checked'));
        $this->assertNotNull($element->getElements()[1]->getAttribute('checked'));
        $this->assertNull($element->getElements()[2]->getAttribute('checked'));
        $this->assertNotNull($element2->getElements()[0]->getAttribute('checked'));
        $this->assertNull($element2->getElements()[1]->getAttribute('checked'));
        $this->assertNull($element2->getElements()[2]->getAttribute('checked'));
    }
}
