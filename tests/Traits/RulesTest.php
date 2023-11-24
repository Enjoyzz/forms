<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Traits;

use Enjoys\Forms\Elements\Text;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Traits\Rules;
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
    public function testAddRuleFail()
    {
        $this->expectException(ExceptionRule::class);
        /** @var Rules $traiRule */
        $traiRule = $this->getMockForTrait(Rules::class);
        $traiRule->addRule('invalid rule class');
    }

    public function testIsRequired()
    {
        $el = new Text('foo');
        $el->addRule(\Enjoys\Forms\Rules::REQUIRED);
        $this->assertTrue($el->isRequired());
    }

    public function testDisableRules()
    {
        $el = new Text('foo');
        $el->addRule(\Enjoys\Forms\Rules::REQUIRED)
            ->addRule(\Enjoys\Forms\Rules::EMAIL);
        $this->assertCount(2, $el->getRules());
        $el->disableRules();
        $this->assertCount(0, $el->getRules());
    }
}
