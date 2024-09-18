<?php

namespace App\Infrastructure\MessagingBroker\Kafka;

use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;


class KafkaHandler
{
    public function __invoke(ConsumerMessage $message, MessageConsumer $consumer)
    {
        $topic = $message->getTopicName();
        $body = $message->getBody();
    }
}
