<?php

include __DIR__ . "/vendor/autoload.php";

use Enjoys\Forms\Form;

$form = new Form();

$form->text('myname', 'Имя')->setDescription('Ваше имя');
$form->text('mysurname', 'Фамилия')->setDescription('Ваша фамилия');
$form->radio('sex', 'Пол')->fill([
    'man' => 'М',
    'woman' => 'Ж'
])->setDescription('Мужчина или Женщина');
$form->select('family_status', 'Семейное положение')->fill([
    'married' => 'Женат(а)',
    'notmarried' => 'Холост',
])->setDescription('брак?');
$form->checkbox('intresting', 'Интересы')->fill([
    'sport' => 'Спорт',
    'music' => 'Музыка',
    'art' => 'Исскуство',
    'computer' => 'Компьютер',
])->setDescription('ваши интересы');

$form->tel('myphone', 'Телефон')->setLabel('Phone');
$form->textarea('about', 'Коротко о себе');
$form->submit('submit_btn', 'Отправить анкету');

if (!$form->isSubmitted()) {
    $renderer = new Enjoys\Forms\Renderer\Renderer($form);
    echo $renderer->display();
} else {
    _var_dump($_GET);
}
