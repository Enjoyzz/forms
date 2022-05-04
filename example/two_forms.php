<?php

declare(strict_types=1);

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Rules;

require __DIR__ . '/../vendor/autoload.php';
try {
    $form = new Form();
    $form->text('test')->addRule(Rules::REQUIRED);
    $form->submit('submit1');

    $form2 = new Form();
    $form2->text('test')->addRule(Rules::REQUIRED);
    $form2->submit('submit1');

    if (!$form->isSubmitted()) {
//        var_dump($form->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValues());
        $renderer = new HtmlRenderer($form);
        echo $renderer->output();
    } else {
        var_dump($_REQUEST);
    }

    if (!$form2->isSubmitted()) {
//        var_dump($form->getElement(Form::_TOKEN_SUBMIT_)->getAttribute('value')->getValues());
        $renderer2 = new HtmlRenderer($form2);
        echo $renderer2->output();
    } else {
        var_dump($_REQUEST);
    }

    echo 'После отправки пустого значения должно появится ошибка валидатора, соответсвенно каждому полю';
} catch (Throwable $e) {
    var_dump($e);
}

