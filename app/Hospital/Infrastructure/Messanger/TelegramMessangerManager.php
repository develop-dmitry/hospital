<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Messanger;

use App\Hospital\Application\Messanger\KeyboardAdapter\NutgramInlineKeyboardAdapter;
use App\Hospital\Application\Messanger\KeyboardAdapter\NutgramReplyKeyboardAdapter;
use App\Hospital\Application\Messanger\MessangerHandler\MessangerHandlerRequest;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Client\Exception\ClientNotFoundException;
use App\Hospital\Domain\Client\Exception\FailedClientCreateException;
use App\Hospital\Domain\Client\Interface\ClientBuilderInterface;
use App\Hospital\Domain\Client\Interface\ClientRepositoryInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

class TelegramMessangerManager implements MessangerManagerInterface
{
    private ?MessangerHandlerContainerInterface $textHandlers = null;

    private ?MessangerHandlerContainerInterface $callbackQueryHandlers = null;

    private ?MessangerHandlerContainerInterface $commandHandlers = null;

    public function __construct(
        protected ClientRepositoryInterface              $clientRepository,
        protected ClientBuilderInterface                 $clientBuilder,
        protected MessangerInterface                     $messanger,
        protected Nutgram                                $bot,
        protected KeyboardButtonCallbackBuilderInterface $callbackDataBuilder,
        protected LoggerInterface                        $logger
    ) {
    }

    public function run(): void
    {
        $this->bot->setRunningMode(Webhook::class);

        if ($this->textHandlers) {
            foreach ($this->textHandlers->getHandlers() as $name => $handler) {
                $this->bot->onText($name, function () use ($handler) {
                    $handler->handler(
                        $this->getClient(),
                        $this->getRequest(),
                        $this->messanger
                    );

                    return $this->sendMessage();
                });
            }
        }

        if ($this->callbackQueryHandlers) {
            $this->bot->onCallbackQuery(function () {
                $callbackQuery = $this->getButtonCallbackData();
                $handler = $this->callbackQueryHandlers->getHandler($callbackQuery->getAction());
                $handler->handler(
                    $this->getClient(),
                    $this->getRequest(),
                    $this->messanger
                );

                return $this->sendMessage();
            });
        }

        if ($this->commandHandlers) {
            foreach ($this->commandHandlers->getHandlers() as $command => $handler) {
                $this->logger->info('Регистрация комманды', ['name' => $command]);

                $this->bot->onCommand($command, function () use ($handler) {
                    $handler->handler(
                        $this->getClient(),
                        $this->getRequest(),
                        $this->messanger
                    );

                    return $this->sendMessage();
                });
            }
        }

        $this->bot->run();
    }

    public function setTextHandlers(MessangerHandlerContainerInterface $textHandlers): void
    {
        $this->textHandlers = $textHandlers;
    }

    public function setCallbackQueryHandlers(MessangerHandlerContainerInterface $callbackQueryHandlers): void
    {
        $this->callbackQueryHandlers = $callbackQueryHandlers;
    }

    public function setCommandHandlers(MessangerHandlerContainerInterface $commandHandlers): void
    {
        $this->commandHandlers = $commandHandlers;
    }

    protected function sendMessage(): array|\SergiX44\Nutgram\Telegram\Types\Message\Message|null
    {
        if ($this->messanger->isEditMessage()) {
            return $this->bot->editMessageText(
                $this->messanger->getMessage(),
                $this->getMessageOptions()
            );
        }

        return $this->bot->sendMessage(
            $this->messanger->getMessage(),
            $this->getMessageOptions()
        );
    }

    /**
     * @return Client
     * @throws FailedClientCreateException
     */
    protected function getClient(): Client
    {
        $telegramId = $this->bot->userId();

        try {
            $client = $this->clientRepository->getClientByTelegramId($telegramId);
        } catch (ClientNotFoundException $exception) {
            $client = $this->clientBuilder->setTelegramId($telegramId)->make();
            $clientId = $this->clientRepository->createClient($client);
            $client->setId($clientId);
        }

        return $client;
    }

    protected function getRequest(): MessangerHandlerRequestInterface
    {
        return new MessangerHandlerRequest($this->getButtonCallbackData());
    }

    protected function getButtonCallbackData(): KeyboardButtonCallbackInterface
    {
        if ($this->bot->callbackQuery()) {
            $callbackData = json_decode($this->bot->callbackQuery()->data, true);
        } else {
            $callbackData = [];
        }

        return $this->callbackDataBuilder
            ->setAction($callbackData['action'] ?? '')
            ->setCallbackData($callbackData)
            ->make();
    }

    protected function getMessageOptions(): array
    {
        $options = [];

        $keyboard = $this->messanger->getMessangerKeyboard();

        if ($keyboard) {
            $options['reply_markup'] = $this->adaptKeyboard(
                $keyboard,
                $this->messanger->getMessangerKeyboardType()
            );
        }

        return $options;
    }

    protected function adaptKeyboard(
        KeyboardInterface $messangerKeyboard,
        KeyboardType      $messangerKeyboardType
    ): mixed {
        return match ($messangerKeyboardType) {
            KeyboardType::Inline => new NutgramInlineKeyboardAdapter($messangerKeyboard),
            KeyboardType::Reply => new NutgramReplyKeyboardAdapter($messangerKeyboard),
            default => null
        };
    }
}
