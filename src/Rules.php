<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Rule;

interface Rules
{
    public const CALLBACK = Rule\Callback::class;
    public const CAPTCHA = Rule\Captcha::class;
    public const EMAIL = Rule\Email::class;
    public const EQUAL = Rule\Equal::class;
    public const LENGTH = Rule\Length::class;
    public const REGEXP = Rule\Regexp::class;
    public const REQUIRED = Rule\Required::class;
    public const UPLOAD = Rule\Upload::class;
}
