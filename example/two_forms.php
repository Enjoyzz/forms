<?php

declare(strict_types=1);

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Rules;

require __DIR__ . '/../vendor/autoload.php';
try {
    $form = new Form(id: 'form1');
    $form->text('test')->addRule(Rules::REQUIRED);
    $form->submit('submit1');

    $form2 = new Form(id: 'form2');
    $form2->text('test')->addRule(Rules::REQUIRED);
    $form2->submit('submit1');

    echo 'Чтобы правильно работали несколько форм (две или более) на одной странице, нужно установить им id. Есть несколько путей:
     - при создании объекта через параметр id; - через метод setId(). При установке id через setAttribute() - работать не будет.';

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

    echo 'После отправки пустого значения должно появится ошибка валидатора, соответственно каждому полю';
} catch (Throwable $e) {
    var_dump($e);
}

