<?php

declare(strict_types=1);

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;

require __DIR__ . '/../vendor/autoload.php';

$form = new Form();
$form->setDefaults([
    'radio' => 4,
    'radio2' => 3,
    'radio-3' => 1,
    'super' => 'super',
    'super-text' => 'super-text',
]);


$form->radio('radio', 'radio')->setDescription('checked: 4')->fill([1,2,3,4,5], true);
$form->radio('radio2', 'radio2')->setDescription('checked: 3')->fill([1,2,3], true);
$form->radio('radio-3', 'radio-3')->setDescription('checked: 1')->fill([1,2,3], true);
$form->text('super-text', 'super-text')->setDescription('need fill `super-text`');
$form->text('super', 'super')->setDescription('need fill `super`');

$renderer = new HtmlRenderer($form);
echo $renderer->output();
