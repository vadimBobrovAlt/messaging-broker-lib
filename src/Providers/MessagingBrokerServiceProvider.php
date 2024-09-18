<?php

namespace bobrovva\messaging_broker_lib\Providers;

use bobrovva\messaging_broker_lib\Commands\MessagingBrokerHandle;
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
        $this->app->bind(MessagingBrokerInterface::class, fn($app) => new KafkaBroker());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MessagingBrokerHandle::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../Enums/MessagingBrokerGroupEnum.php' => base_path('app/Infrastructure/MessagingBroker/Enums/MessagingBrokerGroupEnum.php'),
            __DIR__ . '/../Enums/MessagingBrokerTopicEnum.php' => base_path('app/Infrastructure/MessagingBroker/Enums/MessagingBrokerTopicEnum.php'),
            __DIR__ . '/../Kafka/KafkaHandler.php' => base_path('app/Infrastructure/MessagingBroker/Kafka/KafkaHandler.php'),
        ], 'messaging-broker');
    }
}
