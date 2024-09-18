<?php

namespace bobrovva\messaging_broker_lib\Helpers;

class Loader
{
    public static function loadEnum(string $enumName)
    {
        $publishedPath = base_path("app/Infrastructure/MessagingBroker/Enums/{$enumName}.php");
        $defaultPath = __DIR__ . "/../src/Enums/{$enumName}.php";

        if (file_exists($publishedPath)) {
            return require $publishedPath;
        }

        return require $defaultPath;
    }

    public static function loadHandler()
    {
        $publishedPath = base_path('app/Infrastructure/MessagingBroker/Kafka/KafkaHandler.php');
        $defaultPath = __DIR__ . '/../src/Kafka/KafkaHandler.php';

        if (file_exists($publishedPath)) {
            return require $publishedPath;
        }

        return require $defaultPath;
    }
}