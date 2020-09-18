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
    private function setAttribute(string $name, string $value): void {
        if (isset($this->attributes[$name]) && in_array($name, ['class'])) {
            $this->attributes[$name] = $this->attributes[$name] . " " . $value;
            return;
        }
        $this->attributes[$name] = $value;
    }

    /**
     * 
     * @return string
     */
    public function getAttributes(): string {
        $str = [];
        foreach ($this->attributes as $key => $value) {

            if (is_null($value)) {
                $str[] = " {$key}";
                continue;
            }
            $str[] = " {$key}=\"{$value}\"";
        }
        return implode("", $str);
    }

}
