<?php

include __DIR__ . "/../vendor/autoload.php";

new Session();

use Enjoys\Forms\Captcha\Defaults\Defaults;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Session\Session;

$form = new Form();

$form->text('myname', 'Имя');
$form->captcha(
    (new Defaults())->setOptions([
        'size' => 1,
        'width' => 100, //default: 150
        'height' => 20, //default: 50
        //'chars' => 'abc...'
    ])
);
$form->submit('submit_btn', 'Отправить анкету');

if ($form->isSubmitted()) {
    var_dump($_GET);
}

$renderer = new HtmlRenderer($form);
echo $renderer->output();
