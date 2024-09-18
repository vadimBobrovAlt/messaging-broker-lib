<?php

namespace App\Infrastructure\MessagingBroker\Enums;

use bobrovva\messaging_broker_lib\Enums\Traits\EnumHelper;

enum MessagingBrokerTopicEnum: string
{
    use EnumHelper;

    case BASE = 'base';
}
