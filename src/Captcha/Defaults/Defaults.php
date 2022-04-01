<?php

declare(strict_types=1);

namespace Enjoys\Forms\Captcha\Defaults;

use Enjoys\Forms\Attribute;
use Enjoys\Forms\Captcha\CaptchaBase;
use Enjoys\Forms\Captcha\CaptchaInterface;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Session\Session as Session;

class Defaults extends CaptchaBase implements CaptchaInterface
{

    private string $code = '';
    private Session $session;

    public function __construct(?string $message = null)
    {
        $this->session = new Session();

        $this->setName('captcha_defaults');
        if (is_null($message)) {
            $message = 'Не верно введен код';
        }
        $this->setRuleMessage($message);
    }

    /**
     * @psalm-suppress PossiblyNullReference
     * @param Ruled&Element $element
     * @return bool
     */
    public function validate(Ruled $element): bool
    {
        $method = $this->getRequestWrapper()->getRequest()->getMethod();
        $requestData = match(strtolower($method)){
            'get' => $this->getRequestWrapper()->getQueryData()->getAll(),
            'post' => $this->getRequestWrapper()->getPostData()->getAll(),
            default => []
        };

        $value = \getValueByIndexPath($element->getName(), $requestData);

        if ($this->session->get($element->getName()) !== $value) {
            $element->setRuleError($this->getRuleMessage());
            return false;
        }
        return true;
    }

    public function renderHtml(Element $element): string
    {
        $element->setAttrs(
            Attribute::createFromArray([
                'type' => 'text',
                'autocomplete' => 'off'
            ])
        );

        $this->generateCode($element);
        $img = $this->createImage(
            $this->getCode(),
            (int)$this->getOption('width', 150),
            (int)$this->getOption('height', 50)
        );

        //dump($this->session->get($this->getName()));
        $html = '';

//        if ($this->element->isRuleError()) {
//            $html .= "<p style=\"color: red\">{$this->element->getRuleErrorMessage()}</p>";
//        }
        $html .= '<img alt="captcha image" src="data:image/jpeg;base64,' . $this->getBase64Image(
                $img
            ) . '" /><br /><input' . $element->getAttributesString() . '>';

        return $html;
    }

    private function generateCode(Element $element): void
    {
        $max = (int)$this->getOption('size', 6);
        $chars = $this->getOption('chars', 'qwertyuiopasdfghjklzxcvbnm1234567890');
        $size = StrLen($chars) - 1;
        // Определяем пустую переменную, в которую и будем записывать символы.
        $code = '';
        // Создаём пароль.
        while ($max--) {
            $code .= $chars[rand(0, $size)];
        }
        $this->code = $code;
        $this->session->set(
            [
                $element->getName() => $this->code
            ]
        );
    }

    public function getCode(): string
    {
        return $this->code;
    }


    private function createImage(string $code, int $width = 150, int $height = 50): \GdImage
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
                $x = (int)($width * 0.08);
            } else {
                $x = (int)($x + ($width * 0.8) / \count($letters) + \rand(0, (int)($width * 0.01)));
            }

            if ($h == rand(1, 2)) {
                $y = (int)((($height * 1) / 4) + \rand(0, (int)($height * 0.1)));
            } else {
                $y = (int)((($height * 1) / 4) - \rand(0, (int)($height * 0.1)));
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


    private function getBase64Image(\GdImage $img): string
    {
        \ob_start();
        \imagejpeg($img, null, 80);
        $img_data = \ob_get_contents();
        \ob_end_clean();
        return \base64_encode($img_data);
    }
}
