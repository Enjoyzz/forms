<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Traits\Reflection;
use Tests\Enjoys\Forms\_TestCase;

class CheckboxTest extends _TestCase
{
    use Reflection;

    public function testCheckRemoveAttributeName()
    {
        $el = new Checkbox('foo');
        $this->assertNull($el->getAttr('name'));
    }

    public function testFlushPrefix()
    {
        (new Checkbox('foo'))->setPrefixId('test_');
        $el = new Checkbox('foo');
        $this->assertSame('test_', $el->getPrefixId());

        $el = new Checkbox('foo', flushPrefix: true);
        $this->assertSame('cb_', $el->getPrefixId());
    }


    public function testGetPrefixId()
    {
        $el = new Checkbox('foo', flushPrefix: true);
        $this->assertSame('cb_', $el->getPrefixId());
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
        $this->assertSame('cb_v1', $v1->getAttr('id')->getValueString());
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
        $this->assertSame('i2', $v2->getAttr('id')->getValueString());
    }

    public function test_fill7()
    {
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertInstanceOf(Attribute::class, $v2->getAttr('disabled'));
        $this->assertNotNull($v2->getAttr('id'));
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
        $this->assertSame('prefix_v1', $v1->getAttr('id')->getValueString());
    }

    public function test_prefix2()
    {
        $obj = new Checkbox('name', 'title');
        $obj->setPrefixId('prefix_');

        $obj2 = new Checkbox('name', 'title');
        $this->assertSame('prefix_name', $obj2->getAttr('id')->getValueString());
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
        $this->assertSame('id1', $v1->getAttr('id')->getValueString());
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
        $this->assertSame([1,2], $el->getDefaultValue());
        $this->assertNotNull($elements[0]->getAttr('checked'));
        $this->assertNotNull($elements[1]->getAttr('checked'));
        $this->assertNull($elements[2]->getAttr('checked'));
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
        $this->assertNull($elements[0]->getAttr('checked'));
        $this->assertNotNull($elements[1]->getAttr('checked'));
        $this->assertNull($elements[2]->getAttr('checked'));
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
        $cb->resetPrefixId();
        $this->assertStringContainsString(
            '<input type="checkbox" value="foo" id="cb_foo[]" name=""><label for="cb_foo[]">bar</label>',
            $cb->baseHtml()
        );
    }
}
