<?php


namespace Tests\Enjoys\Forms;

use Enjoys\Forms\DefaultsHandler;
use PHPUnit\Framework\TestCase;


class DefaultsHandlerTest extends TestCase
{

    /**
     * @dataProvider data
     */
    public function testConstructWithSubmitted($data, $value, $expectV)
    {
       $handler = new DefaultsHandler($data);
       $this->assertEquals($data, $handler->getDefaults());
       $this->assertEquals($expectV, $handler->getValue($value));
       $this->assertEquals(false, $handler->getValue('notisset'));
    }
    
    public function data(): array
    {
        return [
            [[1,2,3], 1, 2],
            [[1,2=>[5,6],3], 2, [5,6]],
        ];
    }

}
