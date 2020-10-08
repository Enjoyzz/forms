<?php
include __DIR__."/../vendor/autoload.php";

use Enjoys\Forms\Forms;

$form = new Forms();

$form->text('myname', 'Имя');
$form->text('mysurname', 'Фамилия');
$form->radio('sex', 'Пол')->fill([
    'man' => 'М',
    'woman' => 'Ж'
]);
$form->select('family_status', 'Семейное положение')->fill([
    'married' => 'Женат(а)',
    'notmarried' => 'Холост',
]);
$form->checkbox('intresting', 'Интересы')->fill([
    'sport' => 'Спорт',
    'music' => 'Музыка',
    'art' => 'Исскуство',
    'computer' => 'Компьютер',    
]);

$form->tel('myphone', 'Телефон');
$form->textarea('about', 'Коротко о себе');
$form->submit('submit_btn', 'Отправить анкету');

if(!$form->isSubmited()){
    echo $form->display();
}else{
    _var_dump($_GET);
}
