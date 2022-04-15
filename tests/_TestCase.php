<?php

declare(strict_types=1);


namespace Tests\Enjoys\Forms;

use Enjoys\Session\Session;
use PHPUnit\Framework\TestCase;


if (isset($_SESSION['csrf_secret'])){
    unset($_SESSION['csrf_secret']);
}


class _TestCase extends TestCase
{
    protected Session $session;
    protected function setUp(): void
    {
        $this->session = new Session();
    }

    protected function tearDown(): void
    {
        $this->session->delete('csrf_secret');
        unset($this->session);
    }

    public function stringOneLine(string $input, bool $replaceTab = true): string
    {
        if ($replaceTab) {
            $input = str_replace(["\t", str_repeat(" ", 4)], "", $input);
        }

        return str_replace(["\r\n", "\r", "\n"], "", $input);
    }

}