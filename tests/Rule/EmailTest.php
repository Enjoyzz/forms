<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;


class EmailTest extends TestCase
{

    /**
     * @dataProvider dataForTestValidate
     */
    public function testValidate($name, $request, $expect)
    {
        $text = new Text($name);


        $text->setRequest(
            new ServerRequestWrapper(
                new ServerRequest(queryParams: $request, parsedBody: [], method: 'gEt')
            )
        );
        $text->addRule(Rules::EMAIL);
        $this->assertEquals($expect, Validator::check([$text]));
        if(!$expect){
            $this->assertSame('Не правильно введен email', $text->getRuleErrorMessage());
        }

    }

    public function dataForTestValidate()
    {
        return [
            ['foo', ['foo' => 'test@test.com'], true],
            ['foo', ['foo' => 'test@localhost'], false],
            ['foo', ['foo' => '    '], true],
        ];
    }
}
