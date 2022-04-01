<?php


namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Datalist;


class DatalistTest
{

    public function test_init_datalist()
    {
 
        $el = new Datalist('foo', 'title1');
//        $this->assertIn($el instanceof Datalist);
        $this->assertNull($el->getAttr('id')?->getValueString());
        $this->assertSame('foo', $el->getAttr('list')->getValueString());
    }
    
    public function test_basehtml()
    {
        $el = new Datalist('foo', 'title1');
        $this->assertEquals('', $el->baseHtml());
    }
}
