<?php

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Datalist;
use Tests\Enjoys\Forms\_TestCase;

class DatalistTest extends _TestCase
{
    public function testInitDataList()
    {

        $el = new Datalist('foo', 'title1');
        $this->assertSame('foo', $el->getAttribute('id')->getValueString());
        $this->assertSame('foo-list', $el->getAttribute('list')->getValueString());
    }

    public function testBaseHtml()
    {
        $el = new Datalist('foo');
        $el->fill([1,2]);
        $this->assertEquals(<<<HTML
<input id="foo" name="foo" list="foo-list">
<datalist id='foo-list'>
<option value="1">
<option value="2">
</datalist>
HTML, $el->baseHtml());
    }
}
