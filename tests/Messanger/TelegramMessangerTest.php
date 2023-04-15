<?php

declare(strict_types=1);

namespace Tests\Messanger;

use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Keyboard\Keyboard;
use App\Hospital\Domain\Messanger\Messanger;
use Tests\TestCase;

class TelegramMessangerTest extends TestCase
{
    private Keyboard $keyboard;

    public function testIsEditMessage(): void
    {
        $messanger = new Messanger();
        $messanger->editMessage();

        $this->assertTrue($messanger->isEditMessage());
    }

    public function testSetMessage(): void
    {
        $messanger = new Messanger();
        $message = 'Тестовое сообщение';
        $messanger->setMessage($message);

        $this->assertEquals($message, $messanger->getMessage());
    }

    public function testNextHandler(): void
    {
        $messanger = new Messanger();
        $nextHandler = 'test';
        $messanger->setNextHandler($nextHandler);

        $this->assertEquals($nextHandler, $messanger->getNextHandler());
    }

    public function testKeyboardType(): void
    {
        $messanger = new Messanger();
        $messanger->setMessangerKeyboard($this->keyboard, KeyboardType::Inline);

        $this->assertEquals(KeyboardType::Inline, $messanger->getMessangerKeyboardType());
    }

    public function testKeyboard(): void
    {
        $messanger = new Messanger();
        $messanger->setMessangerKeyboard($this->keyboard, KeyboardType::Inline);

        $this->assertEquals($this->keyboard, $messanger->getMessangerKeyboard());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->keyboard = new Keyboard();
    }
}
