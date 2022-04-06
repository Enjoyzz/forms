<?php


namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{


    public function test_validate_true()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'foo' => 'bar',
                'bar' => 'foo',
            ], method: 'get')
        );

        $form = new Form(method: 'get', request: $request);

        $elements = [
            $form->text('foo')->addRule(Rules::REQUIRED),
            $form->text('bar')->addRule(Rules::REQUIRED),
        ];

        $this->assertTrue(Validator::check($elements));
    }

    public function test_validate_false()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'foo' => 'bar',
            ], method: 'get')
        );

        $form = new Form(method: 'get', request: $request);

        $elements = [
            $form->text('foo')->addRule(Rules::REQUIRED),
            $form->text('bar')->addRule(Rules::REQUIRED),
        ];

        $this->assertFalse(Validator::check($elements));
    }

    public function test_validate_without_rules()
    {
        $form = new Form();
        $elements = [
            $form->text('foo'),
            $form->text('bar'),
        ];

        $this->assertTrue(Validator::check($elements));
    }

    public function test_validate_without_elements()
    {
        $this->assertTrue(Validator::check([]));
    }

    public function test_validate_groups_true()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'foo' => 'v_foo'
            ], method: 'get')
        );
        $form = new Form('get', request: $request);
        $group = $form->group();
        $group->textarea('bararea');
        $group->text('foo')->addRule(Rules::REQUIRED);
        $this->assertEquals(true, Validator::check($form->getElements()));
    }

    public function test_validate_groups_false()
    {
        $request = new ServerRequestWrapper(
            new ServerRequest(queryParams: [
                'food' => 'v_foo'
            ], method: 'get')
        );

        $form = new Form('get', request: $request);
        $group = $form->group();
        $group->textarea('bararea');
        $group->reset('reset');
        $group->text('foo')->addRule(Rules::REQUIRED);
        $this->assertEquals(false, Validator::check($form->getElements()));
    }
}
