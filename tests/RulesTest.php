<?php


namespace Tests\Enjoys\Forms;


use Enjoys\Forms\Rules;

class RulesTest extends _TestCase
{

    public function test_setParams_1_0()
    {
        $rules = new Rules('message', [1]);
        $this->assertSame([1], $rules->getParams());
        $this->assertEquals('message', $rules->getMessage());
        $this->assertNull($rules->getParam('not-isset-param'));
        $this->assertSame(1, $rules->getParam(0));
    }

    public function test_setParams_1_1()
    {
        $rules = new Rules('message', 'param');
        $this->assertEquals(['param'], $rules->getParams());
    }

    public function test_setParams_1_2()
    {
        $rules = new Rules('message', [
            'key_param' => 'value_param'
        ]);
        $this->assertEquals('value_param', $rules->getParam('key_param'));
    }
}
