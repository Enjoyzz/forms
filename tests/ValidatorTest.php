<?php


namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Form;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Validator;
use Enjoys\ServerRequestWrapper;
use HttpSoft\ServerRequest\ServerRequestCreator;

class ValidatorTest
{

//
//    public function test_validate_true()
//    {
//        $form = new \Enjoys\Forms\Form([], new \Enjoys\Forms\Http\Request([
//                    'foo' => 'v_foo',
//                    'bar' => 'v_bar'
//        ]));
//
//        $elements = [
//            $form->text('foo')->addRule('required'),
//            $form->text('bar')->addRule('required'),
//        ];
//
//        $this->assertTrue(\Enjoys\Forms\Validator::check($elements));
//    }
//
//    public function test_validate_false()
//    {
//        $form = new \Enjoys\Forms\Form([], new \Enjoys\Forms\Http\Request([
//                    'foo' => 'v_foo',
//        ]));
//
//        $elements = [
//            $form->text('foo')->addRule('required'),
//            $form->text('bar')->addRule('required'),
//        ];
//
//        $this->assertFalse(\Enjoys\Forms\Validator::check($elements));
//    }
//
//    public function test_validate_without_rules()
//    {
//        $form = new \Enjoys\Forms\Form();
//        $elements = [
//            $form->text('foo'),
//            $form->text('bar'),
//        ];
//
//        $this->assertTrue(\Enjoys\Forms\Validator::check($elements));
//    }
//
//    public function test_validate_without_elements()
//    {
//        $this->assertTrue(\Enjoys\Forms\Validator::check([]));
//    }

    public function test_validate_groups_true()
    {


        $request = new ServerRequestWrapper(
                ServerRequestCreator::createFromGlobals(
                        null,
                        null,
                        null,
                        [
                            'foo' => 'v_foo'
                        ]
                )
        );
        $form = new Form([], $request);
        $group = $form->group();
        $group->textarea('bararea');
        $group->text('foo')->addRule(Rules::REQUIRED);
        $this->assertEquals(true, Validator::check($form->getElements()));
    }

    public function test_validate_groups_false()
    {

        $request = new ServerRequestWrapper(
                ServerRequestCreator::createFromGlobals(
                        null,
                        null,
                        null,
                        [
                            'food' => 'v_foo'
                        ]
                )
        );

        $form = new Form([], $request);
        $group = $form->group();
        $group->textarea('bararea');
        $group->reset('reset');
        $group->text('foo')->addRule(Rules::REQUIRED);
        $this->assertEquals(false, Validator::check($form->getElements()));
    }
}
