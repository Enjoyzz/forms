<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Rule;


class CaptchaTest
{

    public function test_validate_success()
    {
        $mock_element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();
        $mock_element->expects($this->any())->method('validate')->will($this->returnValue(true));


        $rule = new Rule\Captcha();
        $this->assertTrue($rule->validate($mock_element));
    }

    public function test_validate_fail()
    {
        $mock_element = $this->getMockBuilder(\Enjoys\Forms\Elements\Captcha::class)
                ->disableOriginalConstructor()
                ->getMock();
        $mock_element->expects($this->any())->method('validate')->will($this->returnValue(false));


        $rule = new Rule\Captcha();
        $this->assertFalse($rule->validate($mock_element));
    }

}
