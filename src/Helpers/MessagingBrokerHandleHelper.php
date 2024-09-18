<?php

namespace bobrovva\messaging_broker_lib\Helpers;

use App\Infrastructure\MessagingBroker\Enums\MessagingBrokerGroupEnum;
use App\Infrastructure\MessagingBroker\Kafka\KafkaHandler;
use bobrovva\messaging_broker_lib\Facades\MessagingBroker;

class MessagingBrokerHandleHelper
{
    /**
     * Обрабатывает сообщения из брокера сообщений, используя указанный обработчик.
     *
     * @param string $group Название группы потребителей. Используется для определения топиков.
     * @param array $topics Список топиков для подписки (опционально). Если не указан, топики будут определены автоматически на основе группы.
     * @param callable $callback Функция обратного вызова для обработки сообщений (опционально). По умолчанию используется `KafkaHandler`.
     *
     * @return void
     */
    public static function handle(string $group, array $topics = [], callable $callback = new KafkaHandler): void
    {
        $groupEnum = MessagingBrokerGroupEnum::tryFrom($group);
        if (!$topics) {
            $topics = MessagingBrokerGroupEnum::getTopicsByGroup($groupEnum);
        }

        MessagingBroker::consume($topics, $callback, ['group' => $group]);
    }

    /**
     * Синхронно обрабатывает сообщения из брокера сообщений с возможностью фильтрации по значению поля.
     *
     * @param array|string $topics Список топиков для подписки.
     * @param string $group Название группы потребителей.
     * @param string|int|null $comparisonValue Значение для сравнения (опционально). Если указано, будет фильтровать сообщения по этому значению.
     * @param string|null $comparisonField Поле для сравнения (опционально). Если указано, будет сравниваться с $comparisonValue.
     * @param int $timeout Таймаут в секундах для ожидания сообщений (по умолчанию 60).
     * @param int $maxMessages Максимальное количество сообщений для получения (по умолчанию 1).
     *
     * @return array|null Возвращает сообщение в виде ассоциативного массива, если оно удовлетворяет условиям фильтрации, иначе null.
     */
    public static function syncHandle(
        array|string    $topics,
        string          $group,
        string|int|null $comparisonValue = null,
        ?string         $comparisonField = null,
        int             $timeout = 20,
        int             $maxMessages = 1
    )
    {
        $response = null;
        MessagingBroker::consume(
            $topics,
            function ($message) use ($comparisonValue, $comparisonField, &$response) {
                $body = $message->getBody();
                if ($comparisonField && $comparisonValue) {
                    if ($body[$comparisonField] === $comparisonValue) {
                        $response = $body;
                        return true;
                    }
                } else {
                    $response = $body;
                    return true;
                }

                return false;
            },
            [
                'group' => $group,
                'timeout' => $timeout,
                'max_messages' => $maxMessages
            ]
        );

        return $response;
    }
}
