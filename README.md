### Библиотека messaging broker

Данная библиотека служит для взаимодействия с системой брокеров сообщений

Опубликовать конфигурацию
```
php artisan vendor:publish --tag=messaging-broker
```

Команда для прослушивания событий
```
php artisan  messaging-broker:handle {group}
```

Пример отправки

```phpt
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
```

Пример получения
```phpt
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
```
