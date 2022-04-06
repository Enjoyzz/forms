<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;


class EmailTest
{

    /**
     * @dataProvider data_for_test_validate
     */
    public function test_validate($name, $request, $expect)
    {

        $text = new Text($name);



        $text->setRequest(new ServerRequestWrapper(
                                ServerRequestCreator::createFromGlobals(
                                        null,
                                        null,
                                        null,
                                        $request,
                                )
        ));
        $text->addRule(Rules::EMAIL);
        $this->assertEquals($expect, Validator::check([$text]));
    }

    public function data_for_test_validate()
    {
        return [
            ['foo', ['foo' => 'test@test.com'], true],
            ['foo', ['foo' => 'test@localhost'], false],
            ['foo', ['foo' => '    '], true],
        ];
    }
}
