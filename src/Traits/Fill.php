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

namespace Enjoys\Forms\Traits;

/**
 *
 * @author deadl
 */
trait Fill
{

    /**
     *
     * @var array
     */
    private $elements = [];
    private $indexKey;
    private string $parentName = '';
    private int $counterId = 0;

    private function getIndexKey()
    {
        return $this->indexKey;
    }
    
    private function setIndexKeyFill($index_key)
    {
        $this->indexKey = $index_key;
    }
    
    public function setParentName(string $parentName)
    {
        $this->parentName = $parentName;
    }
    
    private function getParentName()
    {
        return $this->parentName;
    }
    
    public function setCounterId(int $counterId)
    {
        $this->counterId = $counterId;
    }
    
    private function getCounterId()
    {
        return $this->counterId;
    }

    /**
     *
     * @param array $data
     * @return $this
     */
    public function fill(array $data)
    {

        foreach ($data as $value => $title) {
            $index_key = $this->getIndexKey();

            $attributes = [];

            $_title = $title;
            //$attributes['value'] = $value;

            if (is_array($title)) {
                $_title = $title[0];

                if (isset($title[1]) && is_array($title[1])) {
                    $attributes = array_merge($attributes, $title[1]);
                }
            }

            $class = '\Enjoys\Forms\Elements\\' . \ucfirst($this->type);
        
            $element = new $class($this->formDefaults, $value, $_title);
       
            $element->setParentName($this->getName());
            $element->setCounterId(\count($this->elements));
            
            $element->addAttributes($attributes);

            // Если в атррибутах есть `id` вызываем setId()
            if (isset($attributes['id'])) {
                $element->setId($attributes['id']);
            }
            
       
            if ($this->formDefaults instanceof \Enjoys\Forms\FormDefaults) {
                $element->setFormDefaults($this->formDefaults);
            }
        

            //dump($element->getAttribute('disabled'));
         
            if ($index_key) {
                $this->elements[$$index_key] = $element;
                continue;
            }
            $this->elements[] = $element;
        }
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }
}
