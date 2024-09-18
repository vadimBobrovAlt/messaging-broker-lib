<?php

namespace bobrovva\messaging_broker_lib\Providers;

use bobrovva\messaging_broker_lib\Helpers\Loader;
use bobrovva\messaging_broker_lib\Kafka\KafkaBroker;
use bobrovva\messaging_broker_lib\MessagingBrokerInterface;
use Illuminate\Support\ServiceProvider;


class MessagingBrokerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Loader::loadEnum('MessagingBrokerGroupEnum');
        Loader::loadEnum('MessagingBrokerTopicEnum');
        Loader::loadHandler();

        $this->app->bind(MessagingBrokerInterface::class, fn($app) => new KafkaBroker());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../src/Enums/MessagingBrokerGroupEnum.php' => base_path('app/Infrastructure/MessagingBroker/Enums/MessagingBrokerGroupEnum.php'),
            __DIR__ . '/../src/Enums/MessagingBrokerTopicEnum.php' => base_path('app/Infrastructure/MessagingBroker/Enums/MessagingBrokerTopicEnum.php'),
        ], 'messaging-broker-enums');

        $this->publishes([
            __DIR__ . '/../src/Kafka/KafkaHandler.php' => base_path('app/Infrastructure/MessagingBroker/Kafka/KafkaHandler.php'),
        ], 'messaging-broker-handler');
    }
}
