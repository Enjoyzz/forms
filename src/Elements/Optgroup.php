<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\FillableInterface;
use Enjoys\Forms\Traits\Fill;

/**
 * Class Optgroup
 * @package Enjoys\Forms\Elements
 *
 * variant 1
 * $form->select('foo', 'bar')
 *      ->setOptgroup(
 *          'group1',
 *          [1, 2, 3],
 *          ['class' => 'text-danger']
 *      )->setOptgroup(
 *          'group2',
 *          [4, 5, 6]
 *      );
 *
 * variant 2
 * $select = $form->select('foo', 'bar');
 * $dataGroup = [
 *      'Города' => [
 *          'vrn' => 'Воронеж',
 *          'msk' => 'Москва',
 *      ],
 *      'Страны' => [
 *          'ru' => 'Russia',
 *          'de' => [
 *              'Germany', [
 *                  'disabled'
 *                  ]
 *              ],
 *          'usa' => [
 * 'USA', [
 *                  'class' => 'h1 text-danger'
 *                  ]
 *          ],
 *      ]
 * ];
 *
 * foreach ($dataGroup as $optgroup => $filldata) {
 *      $select->setOptgroup($optgroup, $filldata);
 * }
 * //можно также продолжить заполнять select option'ами без optgroup
 * $select->fill([
 *      1, 2, 3
 * ]);
 *
 * @since 2.4.0
 */
class Optgroup extends Element implements FillableInterface
{
    use Fill;

    protected string $type = 'option';


    /**
     *
     * @param string $title
     * @param string $parentName
     * @param mixed $defaults
     */
    public function __construct(string $title, string $parentName, $defaults = '')
    {
        parent::__construct(\uniqid('optgroup'), $title);
        $this->setAttributes(
            [
                'label' => $title
            ]
        );
        $this->setName($parentName);
        $this->removeAttribute('name');
        $this->removeAttribute('id');
        $this->setDefault($defaults);
    }

    /**
     *
     * @param mixed $value
     * @return $this
     */
    protected function setDefault($value = null): self
    {
        $this->defaultValue = $value;
        return $this;
    }

    public function baseHtml(): string
    {
        return '';
    }
}
