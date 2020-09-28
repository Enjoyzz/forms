<?php

/*
 * The MIT License
 *
 * Copyright 2020 deadl.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Enjoys\Forms\Captcha\Defaults;

use Enjoys\Base\Session\Session as Session;

/**
 * Description of Defaults
 *
 * @author deadl
 */
class Defaults extends \Enjoys\Forms\Element implements \Enjoys\Forms\Interfaces\Captcha {

    use \Enjoys\Traits\Options;

    private $code = '';
    private $img;

    public function __construct($rule_message = null) {
        parent::__construct('captcha_defaults');

        $this->addAttribute([
            'type' => 'text',
            'autocomplete' => 'off'
        ]);



        $this->addRule('captcha', $rule_message);
    }

    public function validate($value) {
        //_var_dump(Session::get($this->getName()), $value);
        if (Session::get($this->getName()) !== $value) {
            return false;
        }
        return true;
    }

    public function renderHtml() {
        $this->initCaptcha();

        Session::set([
            $this->getName() => $this->getCode()
        ]);
        
        //dump(Session::get($this->getName()));
        $html = '';

        if ($this->isRuleError()) {
            $html .= "<p style=\"color: red\">{$this->getRuleMessage()}</p>";
        }
        $html .= '<img src="data:image/jpeg;base64,' . $this->get_base64image() . '" /><br /><input' . $this->getAttributes() . '>';

        return $html;
    }

    private function initCaptcha() {

        // Ширина изображения
        $width = $this->getOption('width', 150);
        // Высота изображения
        $height = $this->getOption('height', 50);
        // Количество символов в коде
        $sign = $this->getOption('sign', 6);
        // Защитный код
        $this->code = "";

        // Инициируем сессию
        //session_start();
        // Символы, используемые в коде
        $letters = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'j', 'k', 'm', 'n',
            'p', 'q', 'r', 's', 't', 'u',
            'v', 'w', 'x', 'y', 'z', '2',
            '3', '4', '5', '6', '7', '8', '9');
        // Компоненты для RGB-цвета
        $figures = array('50', '70', '90', '110',
            '130', '150', '170', '190', '210');

        // Создаем пустое изображение
        $this->img = imagecreatetruecolor($width, $height);

        $background_color = array(mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        // Заливаем фон белым цветом
        $fon = imagecolorallocate($this->img, $background_color[0], $background_color[1], $background_color[2]);
        imagefill($this->img, 0, 0, $fon);


        // Накладываем защитный код
        for ($i = 0; $i < $sign; $i++) {
            //Ориентир
            $h = 1;
            //Рисуем
            $color = imagecolorallocatealpha(
                    $this->img,
                    $figures[rand(0, count($figures) - 1)],
                    $figures[rand(0, count($figures) - 1)],
                    $figures[rand(0, count($figures) - 1)],
                    rand(10, 30));

            // Генерируем случайный символ
            $letter = $letters[rand(0, sizeof($letters) - 1)];

            // Формируем координаты для вывода символа
            if (empty($x))
                $x = $width * 0.08;
            else
                $x = $x + ($width * 0.8) / $sign + rand(0, $width * 0.01);

            if ($h == rand(1, 2))
                $y = (($height * 1) / 4) + rand(0, $height * 0.1);
            else
                $y = (($height * 1) / 4) - rand(0, $height * 0.1);

            // Запоминаем символ в переменной $code

            $this->code .= $letter;
            // Изменяем регистр символа
            if ($h == rand(0, 1))
                $letter = strtoupper($letter);
            // Выводим символ на изображение
            imagestring($this->img, 6, $x, $y, $letter, $color);
        }

        // Помещаем защитный код в сессию
        //$_SESSION['code'] = $code;
        // Выводим изображение
        // header ("Content-type: image/jpeg");

//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
//        header('Cache-Control: no-store, no-cache, must-revalidate');
//        header('Cache-Control: post-check=0, pre-check=0', FALSE);
//        header('Pragma: no-cache');
        // imagejpeg($this->img, XE_ROOTPATH.'/captcha.jpg', '80');
    }

    function getCode() {
        return $this->code;
    }

    private function get_base64image() {
        ob_start();
        imagejpeg($this->img, null, '80');
        $img_data = ob_get_contents();
        ob_end_clean();
        return base64_encode($img_data);
    }

}
