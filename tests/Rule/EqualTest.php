<?php


declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class EqualTest extends TestCase
{

    /**
     * @dataProvider data_for_test_validate
     */
    public function test_validate($type, $name, $request, $rule, $expect)
    {
        $class = "Enjoys\Forms\Elements\\" . $type;
        $text = new $class($name);
        $text->setRequest(
            new ServerRequestWrapper(
                new ServerRequest(queryParams: $request, parsedBody: [], method: 'get')
            )
        );
        $text->addRule(Rules::EQUAL, null, $rule);
        $this->assertEquals($expect, Validator::check([$text]));
    }

    public function data_for_test_validate()
    {
        return [
            ['Text', 'foo', ['foo' => 'test'], ['test'], true],
            ['Text', 'foo', ['foo' => 'valid'], ['test', 'valid'], true],
            ['Text', 'foo', ['foo' => ['2']], ['test', 2], true],
            ['Text', 'foo', ['foo' => ['test']], ['test', 2], true],
            ['Checkbox', 'bar[][][]', ['bar' => ['test']], ['test', 2], true],
            ['Checkbox', 'bar[][][]', ['bar' => [[['failtest']]]], ['test', 2], false], //
            ['Checkbox', 'foo', ['foo' => ['valid']], ['test', 'valid'], true],
            ['Checkbox', 'foo', ['foo' => ['invalid']], ['test', 'valid'], false],
            ['Checkbox', 'foo', ['foo' => [0]], [1], false],
            ['Checkbox', 'foo', ['foo' => [1, 2]], [1], false], //
            ['Checkbox', 'foo', ['foo' => [1, 3]], [1, 2, 3], true],
            ['Text', 'name', ['name' => 'fail'], ['test'], false],
        ];
    }
}
