<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Rule\Required;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;
use Tests\Enjoys\Forms\Reflection;

class RequiredTest extends _TestCase
{
    use Reflection;

    public function test_required_()
    {
        $element = new Checkbox('name');


        $element->setRequest(
            new ServerRequest(queryParams: ['name' => [1, 2]], parsedBody: [], method: 'gEt')
        );
        $element->addRule(Rules::REQUIRED);
        $this->assertTrue(Validator::check([$element]));
    }

    public function test_required_2()
    {
        $element = new Checkbox('name');
        $element->setRequest(
            new ServerRequest(queryParams: ['name' => []], parsedBody: [], method: 'get')
        );
        $element->addRule(Rules::REQUIRED);
        $this->assertFalse(Validator::check([$element]));
        $this->assertSame('Обязательно для заполнения, или выбора', $element->getRuleErrorMessage());
    }

    public function test_required_3()
    {
        $element = new Checkbox('name');
        $element->addRule(Rules::REQUIRED);
        $this->assertFalse(Validator::check([$element]));
    }

    /**
     * @dataProvider dataForTestPrivateMethodCheck
     */
    public function testPrivateMethodCheck($input, $expect)
    {
        $rule = new Required();
        $method = $this->getPrivateMethod(Required::class, 'check');
        $result = $method->invokeArgs($rule, [$input]);
        $this->assertSame($expect, $result);
    }

    public function dataForTestPrivateMethodCheck()
    {
        return [
            [1, true],
            ['    ', false],
            [[], false],
            [[1], true],
        ];
    }
}
