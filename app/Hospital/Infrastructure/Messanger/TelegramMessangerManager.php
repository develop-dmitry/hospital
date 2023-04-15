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
use App\Hospital\Domain\Messanger\Exception\HandlerNotFoundException;
use App\Hospital\Domain\Messanger\Exception\HandlerRepositoryNetworkException;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardInterface;
use App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardType;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface;
use App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerContainerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRepositoryInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerHandlerRequestInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerInterface;
use App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;
use SergiX44\Nutgram\Telegram\Types\Message\Message;

class TelegramMessangerManager implements MessangerManagerInterface
{
    private ?MessangerHandlerContainerInterface $textHandlers = null;

    private ?MessangerHandlerContainerInterface $callbackQueryHandlers = null;

    private ?MessangerHandlerContainerInterface $commandHandlers = null;

    private ?MessangerHandlerContainerInterface $messageHandlers = null;

    private ?Client $client = null;

    public function __construct(
        protected ClientRepositoryInterface              $clientRepository,
        protected ClientBuilderInterface                 $clientBuilder,
        protected MessangerInterface                     $messanger,
        protected Nutgram                                $bot,
        protected KeyboardButtonCallbackBuilderInterface $callbackDataBuilder,
        protected MessangerHandlerRepositoryInterface    $messangerHandlerRepository,
        protected LoggerInterface                        $logger
    ) {
    }

    public function run(): void
    {
        $this->bot->setRunningMode(Webhook::class);

        if ($this->textHandlers) {
            foreach ($this->textHandlers->getHandlers() as $name => $handler) {
                $this->bot->onText($name, function () use ($handler) {
                    $this->executeHandler($handler);
                });
            }
        }

        if ($this->callbackQueryHandlers) {
            $this->bot->onCallbackQuery(function () {
                $callbackQuery = $this->getButtonCallbackData();
                $handler = $this->callbackQueryHandlers->getHandler($callbackQuery->getAction());

                $this->executeHandler($handler);
            });
        }

        if ($this->commandHandlers) {
            foreach ($this->commandHandlers->getHandlers() as $command => $handler) {
                $this->bot->onCommand($command, function () use ($handler) {
                    return $this->executeHandler($handler);
                });
            }
        }

        $this->bot->onMessage(function () {
            $nextHandler = $this->getNextHandler();

            if (!$nextHandler) {
                $this->messanger->setMessage('Я не знаю такой команды :(');

                return $this->sendMessage();
            }

            return $this->executeHandler($nextHandler);
        });

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

    public function setMessageHandlers(MessangerHandlerContainerInterface $messageHandlers): void
    {
        $this->messageHandlers = $messageHandlers;
    }

    /**
     * @throws FailedClientCreateException
     */
    protected function executeHandler(MessangerHandlerInterface $handler): array|Message|null
    {
        $client = $this->getClient();

        $handler->handler($client, $this->getRequest(), $this->messanger);

        $this->setNextHandler();

        return $this->sendMessage();
    }

    /**
     * @return MessangerHandlerInterface|null
     * @throws FailedClientCreateException
     */
    protected function getNextHandler(): ?MessangerHandlerInterface
    {
        try {
            $nextHandler = $this->messangerHandlerRepository->getNextHandler($this->getClient());

            return $this->messageHandlers->getHandler($nextHandler);
        } catch (HandlerRepositoryNetworkException $exception) {
            $this->logger->error($exception->getMessage());

            return null;
        } catch (HandlerNotFoundException) {
            return null;
        }
    }

    /**
     * @throws FailedClientCreateException
     */
    protected function setNextHandler(): void
    {
        $client = $this->getClient();
        $nextHandler = $this->messanger->getNextHandler();

        if (!$nextHandler) {
            $nextHandler = '';
        }

        try {
            $this->messangerHandlerRepository->setNextHandler($nextHandler, $client);
        } catch (HandlerRepositoryNetworkException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    protected function sendMessage(): array|Message|null
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
        if (is_null($this->client)) {
            $telegramId = $this->bot->userId();

            try {
                $client = $this->clientRepository->getClientByTelegramId($telegramId);
            } catch (ClientNotFoundException $exception) {
                $client = $this->clientBuilder->setTelegramId($telegramId)->make();
                $clientId = $this->clientRepository->createClient($client);
                $client->setId($clientId);
            }

            $client->setName($this->bot->user()->first_name);

            $this->client = $client;
        }

        return $this->client;
    }

    protected function getRequest(): MessangerHandlerRequestInterface
    {
        $message = $this->bot->message();

        if ($message && $message->text) {
            $text = $message->text;
        } else {
            $text = '';
        }

        return new MessangerHandlerRequest($this->getButtonCallbackData(), $text);
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
