<?php

namespace bobrovva\messaging_broker_lib\Facades;

use bobrovva\messaging_broker_lib\MessagingBrokerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \bobrovva\messaging_broker_lib\MessagingBrokerInterface setConsumerGroup(string $group)
 * @method static bool produce(string $topic, array $message, array $options = [])
 * @method static void consume(array|string $topics, callable $callback, array $options = [])
 * @method static void ack($message)
 * @method static void nack($message)
 *
 * @see \bobrovva\messaging_broker_lib\MessagingBrokerInterface
 */
class MessagingBroker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MessagingBrokerInterface::class;
    }
}
