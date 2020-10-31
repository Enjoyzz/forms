<?php
include __DIR__."/vendor/autoload.php";

use Enjoys\Forms\Form;

$form = new Form();

$form->text('myname', 'Имя');
$form->captcha();
$form->submit('submit_btn', 'Отправить анкету');

if(!$form->validate()){
    _var_dump($_GET);
}

echo $form->display();
