<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Fillable;
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
class Optgroup extends Element implements Fillable
{
    use Fill;

    protected string $type = 'option';


    public function __construct(string $title, string $parentName, mixed $defaults = '')
    {
        parent::__construct(\uniqid('optgroup'), $title);
        $this->setAttributes(
            AttributeFactory::createFromArray([
                'label' => $title
            ])
        );
        $this->setName($parentName);
        $this->getAttributeCollection()
            ->remove('name')
            ->remove('id');
        $this->setDefault($defaults);
    }


    protected function setDefault(mixed $value = null): self
    {
        $this->setDefaultValue($value);
        return $this;
    }

    public function baseHtml(): string
    {
        return '';
    }
}
