<?php

namespace App\Test;

use bobrovva\messaging_broker_lib\Enums\MessagingBrokerTopicEnum;

class KafkaHandlerExample
{
    /**
     * Обрабатывает полученное сообщение из Kafka.
     *
     * Этот метод вызывается, когда поступает сообщение от Kafka. В зависимости от темы сообщения, метод выполняет соответствующую логику обработки.
     *
     * @param ConsumerMessage $message Объект сообщения, содержащий тему и тело сообщения.
     * @param MessageConsumer $consumer Объект потребителя сообщения, который может быть использован для дополнительных действий или логики обработки.
     *
     * @return void
     */
    public function __invoke(ConsumerMessage $message, MessageConsumer $consumer)
    {
        $topic = $message->getTopicName();
        $body = $message->getBody();

        switch ($topic) {
            case MessagingBrokerTopicEnum::BASE->value:
                // Отправляем в очередь на обработку или реализуем логику обработки передав $body
                break;
        }
    }
}
