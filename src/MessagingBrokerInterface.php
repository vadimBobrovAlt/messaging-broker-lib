<?php

namespace bobrovva\messaging_broker_lib;

interface MessagingBrokerInterface
{
    public function produce(string $topic, array $message, array $options = []): bool;

    public function consume(array|string $topics, callable $callback, array $options = []): void;

    public function setConsumerGroup(string $group): self;

    public function ack($message): void;

    public function nack($message): void;
}
