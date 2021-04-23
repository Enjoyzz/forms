<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Elements\Radio;
use Enjoys\Forms\Form;
use PHPUnit\Framework\TestCase;

/**
 * Description of RadioTest
 *
 * @author deadl
 */
class CheckboxTest extends TestCase
{



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
        $obj = new Checkbox( 'name', 'title');
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
        $this->assertSame('cb_v1', $v1->getAttribute('id'));
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
        $this->assertSame('i2', $v2->getAttribute('id'));
    }

    public function test_fill7()
    {
        $elements = $this->filldata();
        /** @var Radio $v2 */
        $v2 = $elements[1];
        $this->assertNull($v2->getAttribute('disabled'));
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
        $this->assertSame('prefix_v1', $v1->getAttribute('id'));
    }

    public function test_prefix2()
    {

        $obj = new Checkbox('name', 'title');
        $obj->setPrefixId('prefix_');

        $obj2 = new Checkbox( 'name', 'title');
        $this->assertSame('prefix_name', $obj2->getAttribute('id'));
    }

    public function test_prefix3()
    {
        $obj = new Checkbox( 'name', 'title');
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
        $this->assertSame('id1', $v1->getAttribute('id'));
    }

    public function test_count_checkbox_element()
    {

        $obj = new Checkbox('name', 'title');
        $obj->fill([
            1, 2, 3
        ], true);

        $this->assertCount(3, $obj->getElements());
    }

    public function test_count_checkbox_element2()
    {
 
        $obj = new Checkbox('name', 'title');
        $obj->fill([
            1, 1, 3
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
                1, 2
            ]
        ]);
        $obj = $form->checkbox('name', 'title')->fill([1, 2, 3], true);
        $elements = $obj->getElements();
        $this->assertNull($elements[0]->getAttribute('checked'));
        $this->assertNull($elements[1]->getAttribute('checked'));
        $this->assertFalse($elements[2]->getAttribute('checked'));
    }

    public function test_setDefault_simple()
    {
        $form = new Form();
        $form->setOption('Defaults', [
            'name' => ['baaa']
        ]);
        $radio = $form->checkbox('name', 'title')->fill([
            'val1' => 'Hello',
            'baaa' => 'Hello2',
            'aggg' => 5,
        ]);
        $elements = $radio->getElements();
        $this->assertFalse($elements[0]->getAttribute('checked'));
        $this->assertNull($elements[1]->getAttribute('checked'));
        $this->assertFalse($elements[2]->getAttribute('checked'));
    }
    
    public function test_basehtml()
    {
        $cb = new Checkbox('foo', 'bar');
        $cb->resetPrefixId();
        $this->assertStringContainsString('<input type="checkbox" id="cb_foo[]" value="foo" name=""><label for="cb_foo[]">bar</label>', $cb->baseHtml());
    }
}
