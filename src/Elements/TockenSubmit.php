<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Form;


class TockenSubmit extends Hidden
{

    private string $token;

    public function __construct(string $value)
    {
        $this->token = $value;
        parent::__construct(Form::_TOKEN_SUBMIT_, $this->token);
    }

    public function getSubmitted(): bool
    {
        $rule = new \Enjoys\Forms\Rule\Submit(null, $this->token);
        return $rule->validate($this);
    }
}
