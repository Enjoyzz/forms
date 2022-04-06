<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;


class RequiredTest extends TestCase
{

    public function test_required_()
    {
        $element = new Checkbox('name');


        $element->setRequest(
            new ServerRequestWrapper(
                new ServerRequest(queryParams: ['name' => [1, 2]], parsedBody: [], method: 'get')
            )
        );
        $element->addRule(Rules::REQUIRED);
        $this->assertTrue(Validator::check([$element]));
    }

    public function test_required_2()
    {
        $element = new Checkbox('name');
        $element->setRequest(
            new ServerRequestWrapper(
                new ServerRequest(queryParams: ['name' => []], parsedBody: [], method: 'get')
            )
        );
        $element->addRule(Rules::REQUIRED);
        $this->assertFalse(Validator::check([$element]));
    }

    public function test_required_3()
    {
        $element = new Checkbox('name');
        $element->addRule(Rules::REQUIRED);
        $this->assertFalse(Validator::check([$element]));
    }
}
