<?php

declare(strict_types=1);

use Enjoys\Forms\Elements\Checkbox;
use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;

require __DIR__ . '/../vendor/autoload.php';

$form = new Form();
$form->checkbox('checkbox', 'checkbox')->fill([1,2,3]);
$form->checkbox('checkbox2', 'checkbox2')->fill([1,2,3]);
$form->checkbox('checkbox3', 'checkbox3')->setPrefixId('test_')->fill([1,2,3]);
$form->checkbox('checkbox4', 'checkbox4')->addElements([
    new Checkbox('first', 'Yes', false),
    new Checkbox('second', 'Second', false),
]);
$renderer = new HtmlRenderer($form);
echo $renderer->output();
