<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Exception;

use Enjoys\Forms\Exception\CsrfAttackDetected;
use PHPUnit\Framework\TestCase;

class CsrfAttackDetectedTest extends TestCase
{

    public function testCsrfAttackException()
    {
        $exception = new CsrfAttackDetected();
        $this->assertSame('CSRF Attack detected', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame(null, $exception->getPrevious());
    }

}
