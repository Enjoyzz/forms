<?php

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Bootstrap4\Bootstrap4;

include __DIR__ . "/../vendor/autoload.php";

$form = new Form();
$form->setDefaults(
    [
        'test' => [2, 4]
    ]
);
$form->header(
    'Иногда нужно разбить чекбоксы по группам'
);
$form->checkbox('test', 'label1 with name="test[]"')->setDescription(
    'т.к. элементы в форму добавляются по имени элемента, 
    необходимо их сделать разными, но в то же время надо чтобы они считались с одинаковыми именами, для этого В НАЧАЛЕ 
    имени надо подставить ПРОБЕЛ, после он уберется и все будет считаться без пробела'
)->fill([1, 2, 3]);
$form->checkbox(' test', 'label2 with name=" test[]"')->fill([4, 5]);
$render = new Bootstrap4([], $form);
?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
<?php
echo $render->render();