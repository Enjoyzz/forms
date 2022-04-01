<?php


namespace Tests\Enjoys\Forms;

use Enjoys\Forms\DefaultsHandler;


class DefaultsHandlerTest
{
    

    /**
     * @dataProvider data
     */
    public function test_construct_with_submitted($data, $value, $expectV)
    {
       $handler = new DefaultsHandler($data);
       $this->assertEquals($data, $handler->getDefaults());
       $this->assertEquals($expectV, $handler->getValue($value));
       $this->assertEquals(false, $handler->getValue('notisset'));
    }
    
    public function data()
    {
        return [
            [[1,2,3], 1, 2],
            [[1,2=>[5,6],3], 2, [5,6]],
        ];
    }

//    public function test_construct_with_notsubmitted()
//    {
//        $form = $this->getMockBuilder(\Enjoys\Forms\Form::class)
//                ->disableOriginalConstructor()
//                ->onlyMethods(['isSubmitted', 'getMethod'])
//                ->getMock();
//        $form->method('isSubmitted')->will($this->returnValue(false));
//        $form->method('getMethod')->will($this->returnValue('GET'));
//
//        $defaults = new \Enjoys\Forms\DefaultsHandler([
//            'data' => 'data'
//                ], $form);
//
//
//        $this->assertEquals('data', $defaults->getValue('data'));
//    }
    //put your code here
}
