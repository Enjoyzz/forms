<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use Enjoys\Traits\Reflection;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;

class RadioTest extends _TestCase
{
    use Reflection;

    public function test_title()
    {
        $obj = new Radio('name', 'title');
        $this->assertSame('title', $obj->getLabel());
    }


    public function testGetPrefixId()
    {
        $el = new Radio('foo');
        $this->assertSame('foo_', $el->getPrefixId());
    }

    public function testCheckRemoveAttributeName()
    {
        $el = new Radio('foo');
        $this->assertNull($el->getAttribute('name'));
    }

    public function test_title2()
    {
        $obj = new Radio('name', 'title');
        $obj->setLabel('title2');
        $this->assertSame('title2', $obj->getLabel());
    }

    public function test_name()
    {
        $obj = new Radio('name', 'title');
        $this->assertSame('name', $obj->getName());
    }

    private function filldata()
    {
        $obj = new Radio('name', 'title');
        $obj->setPrefixId('rb_');
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
        $elements = $obj->getElements();
        return $elements;
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
        $this->assertSame('v1', $v1->getName());
    }

    public function test_fill3()
    {
        $elements = $this->filldata();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('rb_v1', $v1->getAttribute('id')->getValueString());
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
        $this->assertSame('v2', $v2->getName());
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
        $this->markAsRisky();
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertSame('', $v2->getAttribute('disabled')->getValueString());
        $this->assertNotNull($v2->getAttribute('id'));
    }

    public function test_prefix()
    {
        $obj = new Radio('name', 'title');
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
        $obj = new Radio('name', 'title');
        $obj->setPrefixId('prefix_');
        $this->assertSame('name', $obj->getAttribute('id')->getValueString());

        $obj2 = new Radio('name', 'title');
        $this->assertSame('name', $obj2->getAttribute('id')->getValueString());
    }

    public function test_prefix3()
    {
        $obj = new Radio('name', 'title');
        $obj->setPrefixId('prefix_');
        $obj->fill([
            'v1' => [
                't1',
                ['id' => 'id1']
            ]
        ]);

        $elements = $obj->getElements();
        /** @var Radio $v1 */
        $v1 = $elements[0];
        $this->assertSame('id1', $v1->getAttribute('id')->getValueString());
    }

    public function test_count_radio_element()
    {
        $obj = new Radio('name', 'title');
        $obj->fill([
            1,
            2,
            3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_radio_element2()
    {
        $obj = new Radio('name', 'title');
        $obj->fill([
            1,
            1,
            3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_radio_element3()
    {
        $obj = new Radio('name', 'title');
        $obj->fill([1], true)->fill([1], true);

        $this->assertCount(2, $obj->getElements());
    }

    public function test_setDefault()
    {
        $form = new Form();
        $form->setDefaults([
            'name' => [1, 2]
        ]);
        $radio = $form->radio('name', 'title')->fill([1, 2, 3], true);
        $elements = $radio->getElements();
        $this->assertNotNull($elements[0]->getAttribute('checked'));
        $this->assertNotNull($elements[1]->getAttribute('checked'));
        $this->assertNull($elements[2]->getAttribute('checked'));
    }

    public function testSetDefaultIfSubmitted()
    {
        $request = new ServerRequest(parsedBody: [
            'name' => 3,
        ]);

        $form = new Form(request: $request);
        $submitted = $this->getPrivateProperty(Form::class, 'submitted');
        $submitted->setAccessible(true);
        $submitted->setValue($form, true);

        $form->setDefaults([
            'name' => [1, 2]
        ]);
        $radio = $form->radio('name', 'title')->fill([1, 2, 3], true);
        $elements = $radio->getElements();
        $this->assertNull($elements[0]->getAttribute('checked'));
        $this->assertNull($elements[1]->getAttribute('checked'));
        $this->assertNotNull($elements[2]->getAttribute('checked'));
    }


    public function test_setDefault_simple()
    {
        $form = new Form();
        $form->setDefaults([
            'name' => 2
        ]);
        $radio = $form->radio('name', 'title')->fill([1, 2, 3], true);
        $elements = $radio->getElements();
        $this->assertNull($elements[0]->getAttribute('checked'));
        $this->assertNotNull($elements[1]->getAttribute('checked'));
        $this->assertNull($elements[2]->getAttribute('checked'));
    }

    public function test_basehtml()
    {
        $rb = new Radio('foo', 'bar');
        $this->assertStringContainsString(
            '<input type="radio" value="foo" id="foo" name=""><label for="foo">bar</label>',
            $rb->baseHtml()
        );
    }
}
