<?php

declare(strict_types=1);

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Rules;

require __DIR__ . '/../vendor/autoload.php';
try {
    $form = new Form('get');
    $form->text('test')->addRule(Rules::REQUIRED);
    $form->text('test2')->addRule(Rules::REQUIRED);
    $form->submit('submit1');
    if ($form->isSubmitted()) {
        var_dump($_REQUEST);
    }
    $renderer2 = new HtmlRenderer($form);
    echo $renderer2->output();
} catch (Throwable $e) {
    var_dump($e);
}
