<?php

namespace App\Test;


use bobrovva\messaging_broker_lib\Enums\MessagingBrokerGroupEnum;
use bobrovva\messaging_broker_lib\Enums\MessagingBrokerTopicEnum;
use bobrovva\messaging_broker_lib\Facades\MessagingBroker;
use bobrovva\messaging_broker_lib\Helpers\MessagingBrokerHandleHelper;
use Illuminate\Support\Str;

class BaseClass
{
    /**
     * Отправляет тестовое сообщение в брокер сообщений.
     *
     * Использует тему, указанную в `MessagingBrokerTopicEnum::BASE`, и отправляет данные `['test' => 'test']`.
     *
     * @return void
     */
    public function actionSend(): void
    {
        MessagingBroker::produce(MessagingBrokerTopicEnum::BASE->value, ['test' => 'test']);
    }

    /**
     * Отправляет сообщение в брокер сообщений и обрабатывает его синхронно.
     *
     * Сообщение отправляется на тему, указанную в `MessagingBrokerTopicEnum::BASE`.
     * В сообщение также включается уникальный идентификатор запроса, сгенерированный с помощью `Str::uuid()`.
     * После отправки сообщения метод `MessagingBrokerHandleHelper::syncHandle` используется для синхронной обработки сообщения.
     *
     * @return void
     */
    public function actionSendAndHandle(): void
    {
        $topic = MessagingBrokerTopicEnum::BASE->value;
        $requestId = (string)Str::uuid();

        MessagingBroker::produce($topic, ['test' => 'test', 'request_id' => $requestId]);

        $result = MessagingBrokerHandleHelper::syncHandle(
            $topic,
            MessagingBrokerGroupEnum::BASE_GROUP->value,
            $requestId,
            'request_id'
        );
    }
}
