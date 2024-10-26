<?php

namespace bobrovva\messaging_broker_lib\Kafka;

use bobrovva\messaging_broker_lib\MessagingBrokerInterface;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;


/**
 * Класс для работы с Kafka брокером.
 * Реализует интерфейс MessagingBrokerInterface.
 */
class KafkaBroker implements MessagingBrokerInterface
{
    /**
     * Идентификатор группы потребителей.
     *
     * @var string|null
     */
    protected ?string $consumerGroup = null;

    /**
     * Отправляет сообщение в указанный топик.
     *
     * @param string $topic Название топика для публикации сообщения.
     * @param array $message Сообщение для отправки, должно содержать ключи 'headers', 'body' и 'key' (опционально).
     * @param array $options Опции отправки сообщения (опционально).
     *
     * @return bool Возвращает true, если сообщение успешно отправлено, иначе false.
     */
    public function produce(string $topic, array $message, array $options = []): bool
    {
        $message = new Message(
            headers: $message['headers'] ?? [],
            body: $message['body'] ?? $message ?? [],
            key: $message['key'] ?? ''
        );

        $producer = Kafka::publish()->onTopic($topic)->withMessage($message);
        return $producer->send();
    }

    /**
     * Потребляет сообщения из указанных топиков.
     *
     * @param array|string $topics Один или несколько топиков для потребления сообщений.
     * @param callable $callback Функция обратного вызова для обработки сообщений.
     * @param array $options Опции потребления сообщений (опционально):
     *  - 'group' (string): Идентификатор группы потребителей.
     *  - 'timeout' (int): Время ожидания в секундах.
     *  - 'max_messages' (int): Максимальное количество сообщений для обработки.
     *
     * @return void
     */
    public function consume(array|string $topics, callable $callback, array $options = []): void
    {
        if (is_string($topics)) {
            $topics = [$topics];
        }

        $consumer = Kafka::consumer($topics);
        $consumer->withHandler($callback);

        if ($this->consumerGroup) {
            $consumer->withConsumerGroupId($this->consumerGroup);
        }

        if (isset($options['group']) && !$this->consumerGroup) {
            $consumer->withConsumerGroupId($options['group']);
        }

        if (isset($options['timeout'])) {
            $consumer->withMaxTime($options['timeout']);
        }

        if (isset($options['max_messages'])) {
            $consumer->withMaxMessages($options['max_messages']);
        }

        while (true) {
            try {
                $consumer->build()->consume();
            } catch (\Exception $e) {
                Log::error('Kafka is temporarily unavailable: ' . $e->getMessage());
                sleep(5);
            }
        }
    }

    /**
     * Устанавливает идентификатор группы потребителей.
     *
     * @param string $group Идентификатор группы потребителей.
     *
     * @return self Возвращает текущий экземпляр класса для цепочки вызовов.
     */
    public function setConsumerGroup(string $group): self
    {
        $this->consumerGroup = $group;
        return $this;
    }

    /**
     * Подтверждает обработку сообщения (в текущей реализации ничего не делает).
     *
     * @param mixed $message Сообщение, которое должно быть подтверждено.
     *
     * @return void
     */
    public function ack($message): void
    {
    }

    /**
     * Отклоняет обработку сообщения (в текущей реализации ничего не делает).
     *
     * @param mixed $message Сообщение, которое должно быть отклонено.
     *
     * @return void
     */
    public function nack($message): void
    {
    }
}
