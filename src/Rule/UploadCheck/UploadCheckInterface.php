<?php

declare(strict_types=1);


namespace Enjoys\Forms\Rule\UploadCheck;


interface UploadCheckInterface
{
    public const UPLOAD_ERROR_MESSAGE = [
        'unknown' => "Unknown upload error",
        \UPLOAD_ERR_INI_SIZE => "Размер принятого файла превысил максимально допустимый размер,
            который задан директивой upload_max_filesize конфигурационного файла php.ini.",
        \UPLOAD_ERR_FORM_SIZE => "Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме.",
        \UPLOAD_ERR_PARTIAL => "Загружаемый файл был получен только частично.",
        \UPLOAD_ERR_NO_FILE => "Файл не был загружен",
        //Добавлено в PHP 5.0.3.
        \UPLOAD_ERR_NO_TMP_DIR => "Отсутствует временная папка.",
        //Добавлено в PHP 5.1.0.
        \UPLOAD_ERR_CANT_WRITE => "Не удалось записать файл на диск.",
        //Добавлено в PHP 5.2.0.
        \UPLOAD_ERR_EXTENSION => "File upload stopped by extension",
    ];

    public function check(): bool;
}
