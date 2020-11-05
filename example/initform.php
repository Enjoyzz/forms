<?php

include __DIR__ . "/vendor/autoload.php";

use Enjoys\Forms\Form;

Enjoys\Session\Session::start();


$form = new Form();

$form2 = new Form([
    'method' => 'post'
        ]);

$form2 = new Form([
    'method' => 'post',
    'action' => 'index.php'
        ]);
