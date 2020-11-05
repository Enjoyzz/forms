<?php
include __DIR__ . "/vendor/autoload.php";

Enjoys\Session\Session::start();

use Enjoys\Forms\Form;

$filldata = [
    1,
    [2, ['style' => 'font-weight: bold; font-size: 200%']],
    [3, ['disabled']],
    ' 100' => 'Hundred',
    'test' => [
        'Test attributes',
        [
            'class' => 'h1',
            'id' => 'test_id',
            'style' => 'font-weight: bold; font-size: 150%',
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
$form->header('textarea');
$form->textarea('textarea1', 'textarea1')->setCols(10)->setRows(5)->setValue('ddddd');
$form->header('fill uses');

//$form->datalist('DATALIST1', 'Datalist1');
$form->select('select1', 'Select1')->setDescription('Select1 Desc')->fill($filldata);
$form->select('select2', 'Select2')->setDescription('Select2 Desc')->fill($filldata)->setMultiple();

$filldata_optgroup = [
    'Города' => [
        'vrn' => 'Воронеж',
        'msk' => 'Москва',
    ],
    'Страны' => [
        'ru' => 'Russia',
        'de' => [
            'Germany', [
                'disabled'
            ]
        ],
        'usa' => [
            'USA', [
                'class' => 'h1 text-danger'
            ]
        ],
    ]
];
$select3 = $form->select('select3', 'Select3 Optgroup')->setDescription('Select3 Optgroup');
foreach ($filldata_optgroup as $_optgroup => $_filldata) {
    $select3->setOptgroup($_optgroup, $_filldata);
}
$select3->fill([1, 2, 3]);

$select4 = $form->select('select4', 'Select4 Optgroup')->setDescription('Select4 Optgroup')->setMultiple();
foreach ($filldata_optgroup as $optgroup => $__filldata) {
    $select4->setOptgroup($optgroup, $__filldata);
}
$select4->fill([1, 2, 3]);

$select5 = $form->select('select5')
        ->setOptgroup('numbers', [1, 2, 3])
        ->setOptgroup('alpha', ['a', 'b', 'c'])
        ->setMultiple()
        ->setAttribute('size', 10)
;

$form->checkbox('checkbox1', 'Checkbox1')->setDescription('checkboxDesc')->fill($filldata);
$form->radio('radio1', 'radio1')->setDescription('radioDesc')->fill($filldata);
$form->datalist('datalist1', 'datalist1')->setDescription('datalistDesc')->fill($filldata);

$form->header('upload');
$form->file('myfile', 'Файл')->setMaxFileSize(15500)->setDescription('select fole for upload')->addRule(\Enjoys\Forms\Rules::UPLOAD, null, 'required');

$form->header('buttons');
$form->submit('sbmt', 'Send form');
$form->reset('rest', 'Resets');
$form->button('btn', '<img src="images/umbrella.gif" alt="Зонтик" 
          style="vertical-align: middle"> Кнопка с рисунком');
$form->image('sbmtimg', 'http://buildingmaintenancecork.com/wp-content/uploads/2013/05/glossy_blue_play_button_up_10121.png');

//$form->header('group');
//$group = $form->group('Group1', [
//    new Enjoys\Forms\Elements\Text('text2', 'text2'),
//    (new Enjoys\Forms\Elements\Select('select2', 'select2'))->fill([7,8,9])
//]);

if ($form->isSubmitted()) {
    _var_dump($_GET);
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
<?php
echo $form->render(new Enjoys\Forms\Renderer\Bootstrap4\Bootstrap4());
