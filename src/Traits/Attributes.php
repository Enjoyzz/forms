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

namespace Enjoys\Forms\Traits;

/**
 * 
 * @author Enjoys
 */
trait Attributes {
    


    /**
     *
     * @var array
     */
    private array $attributes = [];
    private string $groupAttributes = 'general';
    
    public function setGroupAttributes(string $group) {
        $this->groupAttributes = $group;
        return $this;
    }
    
//    public function getGroupAttributes() {
//        return $this->groupAttributes;
//    }
    
    public function resetGroupAttributes() {
        $this->groupAttributes = 'general';
        return $this;
    }

    /**
     * 
     * @param mixed $attributes
     * @return \self
     */
    public function addAttribute(...$attributes): self {
        //dump($attributes);
        if (is_array($attributes[0])) {
            foreach ($attributes[0] as $name => $value) {
                $this->setAttribute($name, $value);
            }
            return $this;
        }

        $this->setAttribute($attributes[0], (isset($attributes[1])) ? $attributes[1] : null );
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return void
     */
    private function setAttribute(string $name, string $value = null): void {
        if (isset($this->attributes[$this->groupAttributes][$name]) && in_array($name, ['class'])) {
            $this->attributes[$this->groupAttributes][$name] = $this->attributes[$this->groupAttributes][$name] . " " . $value;
            return;
        }
        $this->attributes[$this->groupAttributes][$name] = $value;
    }
    
    public function getAttribute($key) {
        if(isset($this->attributes[$this->groupAttributes][$key])){
            return $this->attributes[$this->groupAttributes][$key];
        }
        return false;
    }

    /**
     * 
     * @return string
     */
    public function getAttributes(): string {
        $str = [];
        if(!isset($this->attributes[$this->groupAttributes])){
            $this->attributes[$this->groupAttributes] = [];
        }
        foreach ($this->attributes[$this->groupAttributes] as $key => $value) {

            if (is_null($value)) {
                $str[] = " {$key}";
                continue;
            }
            $str[] = " {$key}=\"{$value}\"";
        }
        return implode("", $str);
    }
    
    public function removeAttribute($key) : self {
        if(isset($this->attributes[$this->groupAttributes][$key])){
            unset($this->attributes[$this->groupAttributes][$key]);
        }
        return $this;
    }

}
