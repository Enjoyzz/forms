<?php

include __DIR__ . "/vendor/autoload.php";

Enjoys\Session\Session::start();

use Enjoys\Forms\Form;

$filldata = [
    1,
    2,
    3,
    ' 100' => 'Hundred',
    'test' => [
        'Test attributes', 
        [
            'class' => 'h1',
            'style' => 'font-weight: bold; font-size: 150%'
        ]
    ]
];

$form = new Form([
    'method' => 'post'
]);


$form->header('input');

$form->text('text1', 'Text1')
        ->setAttribute('class', 'class1')
        ->setAttributes([
            'id' => 'newid'
        ])
        ->setLabel('Text2override')
        ->setDescription('Desc1')
        ->addClass('class2')
        ->addRule(\Enjoys\Forms\Rules::REQUIRED)
;
$form->color('color1', 'color1');
$form->date('date1', 'date1');
$form->datetime('datetime1', 'datetime1');
$form->datetimelocal('datetimelocal1', 'datetimelocal1');
$form->email('email1', 'email1');
$form->month('month1', 'month1');
$form->number('number1', 'number1');
$form->password('password1', 'password1');
$form->range('range1', 'range1');
$form->search('search1', 'search1');
$form->tel('tel1', 'tel1');
$form->time('time1', 'time1');
$form->url('url1', 'url1');
$form->week('week1', 'week1');


$form->header('fill uses');

//$form->datalist('DATALIST1', 'Datalist1');
$form->select('select1', 'Select1')->setDescription('Desc')->fill($filldata);
$form->checkbox('checkbox1', 'Checkbox1')->setDescription('checkboxDesc')->fill($filldata);
$form->radio('radio1', 'radio1')->setDescription('radioDesc')->fill($filldata);

$form->header('upload');
$form->file('myfile', 'Файл')->setMaxFileSize(15500)->setDescription('select fole for upload')->addRule(\Enjoys\Forms\Rules::UPLOAD, null, 'required');
$form->submit('sbmt', 'Send form');

if ($form->isSubmitted()) {
    _var_dump($_GET);
}

$renderer = new Enjoys\Forms\Renderer\Renderer($form);
echo $renderer->display();
