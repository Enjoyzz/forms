<?php


namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Datalist;
use Tests\Enjoys\Forms\TestCase;


class DatalistTest extends TestCase
{

    public function testInitDataList()
    {
 
        $el = new Datalist('foo', 'title1');
        $this->assertSame('foo', $el->getAttr('id')->getValueString());
        $this->assertSame('foo-list', $el->getAttr('list')->getValueString());
    }
    
    public function testBaseHtml()
    {
        $el = new Datalist('foo', 'title1');
        $this->assertEquals($this->stringOneLine(<<<HTML
<input id="foo" name="foo" list="foo-list">
<datalist id='foo-list'>
</datalist>
HTML), $this->stringOneLine($el->baseHtml()));
    }
}
