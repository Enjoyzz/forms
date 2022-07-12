<?php

declare(strict_types=1);

use Enjoys\Forms\Form;
use Enjoys\Forms\Renderer\Html\HtmlRenderer;
use Enjoys\Forms\Rules;

require __DIR__ . '/../vendor/autoload.php';
try {
    $form = new Form();
    $form->file('test1')
        ->setMultiple()
        ->addRule(Rules::UPLOAD, [
            'required',
            'extensions' => 'jpg, png, jpeg',
        ]);
    $form->submit('submit1');
    if ($form->isSubmitted()) {
        var_dump($_REQUEST, $_FILES);
    }
    $renderer2 = new HtmlRenderer($form);
    echo $renderer2->output();
} catch (Throwable $e) {
    var_dump($e);
}
