<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
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

declare(strict_types=1);

namespace Enjoys\Forms\Captcha\Defaults;

use Enjoys\Session\Session as Session;

/**
 * Description of Defaults
 *
 * @author Enjoys
 */
class Defaults extends \Enjoys\Forms\Captcha\CaptchaBase implements \Enjoys\Forms\Captcha\CaptchaInterface
{

    /**
     *
     * @var string 
     */
    private string $code = '';
    
    /**
     * 
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {

        $this->setName('captcha_defaults');
        if (is_null($message)) {
            $message = 'Не верно введен код';
        }
        $this->setRuleMessage($message);
    }


    /**
     * 
     * @param \Enjoys\Forms\Element $element
     * @return bool
     */
    public function validate(\Enjoys\Forms\Element $element): bool
    {


        $method = $this->getRequest()->getMethod();
        $value = \getValueByIndexPath($element->getName(), $this->getRequest()->$method());

        if (Session::get($element->getName()) !== $value) {
            $element->setRuleError($this->ruleMessage);
            return false;
        }
        return true;
    }

    public function renderHtml(\Enjoys\Forms\Element $element): string
    {
        $element->setAttributes([
            'type' => 'text',
            'autocomplete' => 'off'
        ]);

        $this->generateCode($element);
        $img = $this->createImage($this->getCode(), $this->getOption('width', 150), $this->getOption('height', 50));

        //dump(Session::get($this->getName()));
        $html = '';

//        if ($this->element->isRuleError()) {
//            $html .= "<p style=\"color: red\">{$this->element->getRuleErrorMessage()}</p>";
//        }
        $html .= '<img src="data:image/jpeg;base64,' . $this->getBase64Image($img) . '" /><br /><input' . $element->getAttributesString() . '>';

        return $html;
    }

    private function generateCode(\Enjoys\Forms\Element $element): void
    {
        $max = $this->getOption('size', 6);
        $chars = $this->getOption('chars', 'qwertyuiopasdfghjklzxcvbnm1234567890');
        $size = StrLen($chars) - 1;
        // Определяем пустую переменную, в которую и будем записывать символы.
        $code = null;
        // Создаём пароль.
        while ($max--) {
            $code .= $chars[rand(0, $size)];
        }
        $this->code = $code;
        Session::set([
            $element->getName() => $this->code
        ]);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return false|resource
     */
    private function createImage(string $code, $width = 150, $height = 50)
    {
        // Создаем пустое изображение
        $img = \imagecreatetruecolor($width, $height);

        $background_color = [\mt_rand(200, 255), \mt_rand(200, 255), \mt_rand(200, 255)];
        // Заливаем фон белым цветом
        $background = \imagecolorallocate($img, $background_color[0], $background_color[1], $background_color[2]);
        \imagefill($img, 0, 0, $background);


        // Накладываем защитный код
        $x = 0;
        $letters = \str_split($code);
        $figures = [50, 70, 90, 110, 130, 150, 170, 190, 210];

        foreach ($letters as $letter) {
            //Ориентир
            $h = 1;
            //Рисуем
            $color = \imagecolorallocatealpha(
                    $img,
                    $figures[\rand(0, \count($figures) - 1)],
                    $figures[\rand(0, \count($figures) - 1)],
                    $figures[\rand(0, \count($figures) - 1)],
                    rand(10, 30)
            );


            // Формируем координаты для вывода символа
            if (empty($x)) {
                $x = (int) ($width * 0.08);
            } else {
                $x = (int) ($x + ($width * 0.8) / \count($letters) + \rand(0, (int) ($width * 0.01)));
            }

            if ($h == rand(1, 2)) {
                $y = (int) ((($height * 1) / 4) + \rand(0, (int) ($height * 0.1)));
            } else {
                $y = (int) ((($height * 1) / 4) - \rand(0, (int) ($height * 0.1)));
            }


            // Изменяем регистр символа
            if ($h == \rand(0, 1)) {
                $letter = \strtoupper($letter);
            }
            // Выводим символ на изображение
            \imagestring($img, 6, $x, $y, $letter, $color);
            $x++;
        }

        return $img;
    }

    /**
     * @param false|resource $img
     */
    private function getBase64Image($img): string
    {
        \ob_start();
        \imagejpeg($img, null, 80);
        $img_data = \ob_get_contents();
        \ob_end_clean();
        return \base64_encode($img_data);
    }
}
