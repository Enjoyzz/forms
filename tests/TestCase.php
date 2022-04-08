<?php

declare(strict_types=1);


namespace Tests\Enjoys\Forms;

use Enjoys\Session\Session;

new Session();

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function stringOneLine(string $input): string
    {
        return str_replace(["\n", "\r\n", "\r"], "", $input);
    }
}