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

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Form;
use Enjoys\Forms\Traits\Fill;

/**
 * Description of Optgroup
 *
 * variant 1
 * $form->select('foo', 'bar')
 *      ->optgroup(
 *          'group1',
 *          [1, 2, 3],
 *          ['class' => 'text-danger']
 *      )->optgroup(
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
  'USA', [
 *                  'class' => 'h1 text-danger'
 *                  ]
 *          ],
 *      ]
 * ];
 *
 * foreach ($dataGroup as $optgroup => $filldata) {
 *      $select->optgroup($optgroup, $filldata);
 * }
 * //можно также продолжить заполнять select option'ами без optgroup
 * $select->fill([
 *      1, 2, 3
 * ]);
 *
 * @since 2.4.0
 * @author deadl
 */
class Optgroup extends Element
{
    use Fill;

    protected string $type = 'option';
    private $defaults = '';

    public function __construct(string $title, string $parentName)
    {
        parent::__construct(\uniqid('optgroup'), $title);
        $this->setAttributes([
            'label' => $title
        ]);
        $this->setName($parentName);
        $this->removeAttribute('name');
        $this->removeAttribute('id');
    }

    protected function setDefault(): self
    {
        return $this;
    }

    public function baseHtml(): ?string
    {
        return null;
    }
}
