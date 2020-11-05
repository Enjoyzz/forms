<?php

include __DIR__ . "/vendor/autoload.php";

Enjoys\Session\Session::start();

use Enjoys\Forms\Form;

$form = new Form();

$form->text('myname', 'Имя');
$form->captcha()->setOptions([
    'size' => 1,
    'width' => 100, //default: 150
    'height' => 20, //default: 50
        //'chars' => 'abc...'
]);
$form->submit('submit_btn', 'Отправить анкету');

if ($form->isSubmitted()) {
    _var_dump($_GET);
}

$renderer = new Enjoys\Forms\Renderer\Renderer($form);
echo $renderer->display();
