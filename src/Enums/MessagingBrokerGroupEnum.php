<?php

namespace App\Infrastructure\MessagingBroker\Enums;

use bobrovva\messaging_broker_lib\Enums\Traits\EnumHelper;

enum MessagingBrokerGroupEnum: string
{
    use EnumHelper;

    case BASE_GROUP = 'base_group';

    public static function getTopicsByGroup($enum): array
    {
        return match ($enum) {
            self::BASE_GROUP => [
                MessagingBrokerTopicEnum::BASE->value,
            ],
            default => [],
        };
    }
}
