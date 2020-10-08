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

use Enjoys\Forms\Interfaces;
use Enjoys\Forms\Traits\Attributes;
use Enjoys\Forms\Traits\LabelAttributes;

/**
 * Class Element
 * 
 * 
 * @author Enjoys
 */
class Element implements Interfaces\Element
{

    use Attributes,
        LabelAttributes;

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
//    protected ?string $validate_name = null;

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
//    protected Validator $rule;

    protected $rules = [];
    private $rule_error_message = null;
    private $rule_error = false;
    private array $defaults = [];

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

        $this->setDefault();

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

//    public function setValidateName(string $name): self {
//        $this->validate_name = $name;
//        //if (!empty($this->defaults)) {
//            $this->setDefault();
//        //}
//        return $this;
//    }
//
//    public function getValidateName(): ?string {
//        return $this->validate_name;
//    }

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
    protected function setValue(string $value): self {
        if ($this->getAttribute('value') !== false) {
            return $this;
        }
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

    /**
     * 
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * 
     * @param array $data
     * @return \self
     */
    public function setDefault(): self {
        $value = \Enjoys\Helpers\Arrays::getValueByIndexPath($this->getName(), Forms::getDefaults());

        if (is_array($value)) {

             $this->setValue($value[0]);
               
        }

        if (is_string($value) || is_numeric($value)) {
            $this->setValue($value);
        }
        return $this;
    }

    /**
     * 
     * @param type $path
     * @param type $data
     * @return boolean
     */
//    final protected function getStringValueForSetDefault(string $path, array $data) {
//
//        preg_match_all("/^([\w\d]*)|\[['\"]*(|[a-z0-9_-]+)['\"]*\]/i", $path, $matches);
//
//        if (count($matches[0]) > 0) {
//            //$i = 0;
//            foreach ($matches[0] as $key) {
//                $key = str_replace(['[', ']', '"', '\''], [''], $key);
//
//                if (empty($key)) {
//                    $key = 0;
//                }
//
//                if (isset($data[$key])) {
//                    $data = $data[$key];
//                } else {
//                    return false;
//                }
//            }
//        }
//
//
//        return $data;
//    }

    /**
     * 
     * @param string $rule
     * @param string $message
     * @param array $params
     * @return $this
     */
    public function addRule(string $rule, ?string $message = null, $params = []) {
        $class = "\Enjoys\Forms\Rule\\" . \ucfirst($rule);
        $this->rules[] = new $class($message, $params);
        return $this;
    }

//    public function getRule($d) {
//        return $this->rules[];
//    }    

    /**
     * 
     */
    public function setRuleError($message) {
        $this->rule_error = true;
        $this->rule_error_message = $message;
    }

    public function getRuleErrorMessage() {
        return $this->rule_error_message;
    }

    /**
     * 
     * @return type
     */
    public function isRuleError() {
        return $this->rule_error;
    }

    /**
     * 
     * @return type
     */
    public function getRules() {
        return $this->rules;
    }

}
