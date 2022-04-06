<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;


class RequiredTest
{

    public function test_required_()
    {

        $element = new Checkbox('name');


        $element->setRequest(new ServerRequestWrapper(
                        ServerRequestCreator::createFromGlobals(
                                null,
                                null,
                                null,
                                ['name' => [1, 2]]
                        )
        ));
        $element->addRule(Rules::REQUIRED);
        $this->assertTrue(Validator::check([$element]));
    }

    public function test_required_2()
    {
        $element = new Checkbox('name');
        $element->setRequest(new ServerRequestWrapper(
                        ServerRequestCreator::createFromGlobals(
                                null,
                                null,
                                null,
                                ['name' => []]
                        )
        ));
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
