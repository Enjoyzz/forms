<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Rule;

use Enjoys\Forms\Elements\Hidden;
use Enjoys\Forms\Rule\Submit;
use Enjoys\ServerRequestWrapper;
use HttpSoft\Message\ServerRequest;
use Tests\Enjoys\Forms\_TestCase;

class SubmitTest extends _TestCase
{
    public function testValidateTrue()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'token' => 123
            ], parsedBody: [], method: 'gEt')
        );

        $rule = new Submit('123');
        $rule->setRequest($request);
        $this->assertEquals(true, $rule->validate(new Hidden('token')));
    }

    public function testValidateFalse()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'token_' => 123
            ], parsedBody: [], method: 'gEt')
        );

        $rule = new Submit('123');
        $rule->setRequest($request);
        $this->assertEquals(false, $rule->validate(new Hidden('token')));
    }
}
