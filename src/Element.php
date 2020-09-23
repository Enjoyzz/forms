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

namespace Enjoys\Forms;

/**
 * Class Element
 * 
 * 
 * @author Enjoys
 */
class Element {


    use Traits\Attributes, Traits\LabelAttributes;

    /**
     *
     * @var string
     */
    protected string $type;

    /**
     *
     * @var string|null  
     */
    protected ?string $name = null;

    /**
     *
     * @var string|null  
     */
    protected ?string $id = null;

    /**
     *
     * @var string|null   
     */
    protected ?string $title = null;

    /**
     *
     * @var string|null   
     */
    protected ?string $description = null;

    /**
     *
     * @var string|null   
     */
    protected ?string $value = null;

    /**
     * 
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title = null) {
        $this->setName($name);
        if (!is_null($title)) {
            $this->setTitle($title);
        }

    }

    /**
     * 
     * @param string $name
     * @return \self
     */
    public function setName(string $name): self {
        $this->name = $name;
        $this->setId($this->name);
        $this->setAttribute('name', $this->name);
        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     * @return \self
     */
    public function setId(string $id): self {
        $this->id = $id;
        $this->setAttribute('id', $this->id);
        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getId(): ?string {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * 
     * @param string $value
     * @return \self
     */
    public function setValue(string $value): self {
        $this->value = $value;
        $this->setAttribute('value', $this->value);
        return $this;
    }

    /**
     * 
     * @param string $title
     * @return \self
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * 
     * @param string $description
     * @return \self
     */
    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }
    
    public function getDescription(): ?string {
        return $this->description;
    }    

    
    public function setDefault(array $data) {
        
    }


}
