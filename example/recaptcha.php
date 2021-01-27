<?php

include __DIR__ . "/../vendor/autoload.php";

new \Enjoys\Session\Session();

use Enjoys\Forms\Form;

$form = new Form();

$form->text('myname', 'Имя');
$form->captcha((new \Enjoys\Forms\Captcha\reCaptcha\reCaptcha())->setOptions(
    [
        'language' => 'ru',
        'publickey' => '6LdUGNEZAAAAANA5cPI_pCmOqbq-6_srRkcGOwRy', //localhost
        'privatekey' => '6LdUGNEZAAAAAPPz685RwftPySFeCLbV1xYJJjsk', //localhost
    ]
));
$form->submit('submit_btn', 'Отправить анкету');

if ($form->isSubmitted()) {
    _var_dump($_GET);
}

$renderer = new \Enjoys\Forms\Renderer\Bootstrap4\Bootstrap4([], $form);
echo $renderer->render();