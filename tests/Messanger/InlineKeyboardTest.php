<?php

declare(strict_types=1);

namespace Tests\Messanger;

use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface;
use App\Hospital\Domain\Messanger\Keyboard\InlineKeyboard;
use App\Hospital\Domain\Messanger\Keyboard\Keyboard;
use Tests\TestCase;

class InlineKeyboardTest extends TestCase
{
    private KeyboardButtonBuilderInterface $buttonBuilder;

    public function testAddRow(): void
    {
        $keyboard = new InlineKeyboard();

        $buttonFirst = $this->buttonBuilder
            ->setText('test')
            ->makeReplyButton();
        $buttonSecond = $this->buttonBuilder
            ->setText('test2')
            ->makeReplyButton();

        $keyboard->addRow($buttonFirst);
        $keyboard->addRow($buttonSecond);

        $this->assertCount(2, $keyboard->getRows());
    }

    public function testAddOneRow(): void
    {
        $keyboard = new InlineKeyboard();

        $buttonFirst = $this->buttonBuilder
            ->setText('test')
            ->makeReplyButton();
        $buttonSecond = $this->buttonBuilder
            ->setText('test2')
            ->makeReplyButton();

        $keyboard->addRow($buttonFirst, $buttonSecond);

        $this->assertCount(1, $keyboard->getRows());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->buttonBuilder = $this->app->make(KeyboardButtonBuilderInterface::class);
    }
}
