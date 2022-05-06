<?php

declare(strict_types=1);

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Rules;

require __DIR__ . '/../vendor/autoload.php';
try {
    $form = new Form(action: '/process.php');
    $form->text('test')->addRule(Rules::REQUIRED);
    $form->submit('submit1');

    if (!$form->isSubmitted()) {
        var_dump($form->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValues());
        $renderer = new HtmlRenderer($form);
        echo $renderer->output();
    } else {
        var_dump($_REQUEST);
    }
} catch (Throwable $e) {
    var_dump($e);
}

