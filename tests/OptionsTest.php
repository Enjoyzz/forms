<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms;

use Enjoys\Forms\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testSetMethod(): void
    {
        $options = new Options([
            'method' => 'get'
        ]);

        $this->assertSame('GET', $options->getMethod());
    }

    public function testSetMethodInvalid(): void
    {
        $options = new Options([
            'method' => 'invalid'
        ]);

        $this->assertSame('POST', $options->getMethod());
    }

    public function testSetAction()
    {
        $options = new Options([
            'action' => 'handler.php'
        ]);
        $this->assertSame('handler.php', $options->getAction());
        $options->setAction(null);
        $this->assertNull($options->getAction());
    }
}
