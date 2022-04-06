<?php


namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use PHPUnit\Framework\TestCase;


class RadioTest extends TestCase
{

    public function test_title()
    {
        $obj = new Radio('name', 'title');
        $this->assertSame('title', $obj->getLabel());
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
        $this->assertSame('rb_v1', $v1->getAttr('id')->getValueString());
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
        $this->assertSame('i2', $v2->getAttr('id')->getValueString());
    }

    public function test_fill7()
    {
        $this->markAsRisky();
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertSame('', $v2->getAttr('disabled')->getValueString());
        $this->assertNotNull($v2->getAttr('id'));
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
        $this->assertSame('prefix_v1', $v1->getAttr('id')->getValueString());
    }

    public function test_prefix2()
    {

        $obj = new Radio('name', 'title');
        $obj->setPrefixId('prefix_');

        $obj2 = new Radio('name', 'title');
        $this->assertSame('prefix_name', $obj2->getAttr('id')->getValueString());
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
        $this->assertSame('id1', $v1->getAttr('id')->getValueString());
    }

    public function test_count_radio_element()
    {

        $obj = new Radio('name', 'title');
        $obj->fill([
            1, 2, 3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_radio_element2()
    {

        $obj = new Radio('name', 'title');
        $obj->fill([
            1, 1, 3
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
        $this->assertNotNull($elements[0]->getAttr('checked'));
        $this->assertNotNull($elements[1]->getAttr('checked'));
        $this->assertNull($elements[2]->getAttr('checked'));
    }


    public function test_setDefault_simple()
    {

        $form = new Form();
        $form->setDefaults([
            'name' => 2
        ]);
        $radio = $form->radio('name', 'title')->fill([1, 2, 3], true);
        $elements = $radio->getElements();
        $this->assertNull($elements[0]->getAttr('checked'));
        $this->assertNotNull($elements[1]->getAttr('checked'));
        $this->assertNull($elements[2]->getAttr('checked'));
    }

    public function test_basehtml()
    {
        $rb = new Radio('foo', 'bar');
        $rb->resetPrefixId();
        $this->assertStringContainsString('<input type="radio" value="foo" id="rb_foo" name=""><label for="rb_foo">bar</label>', $rb->baseHtml());
    }
}
