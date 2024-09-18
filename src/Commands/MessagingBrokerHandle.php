<?php

namespace bobrovva\messaging_broker_lib\Commands;

use bobrovva\messaging_broker_lib\Helpers\MessagingBrokerHandleHelper;
use Illuminate\Console\Command;

class MessagingBrokerHandle extends Command
{
    protected $signature = 'messaging-broker:handle {group}';
    protected $description = 'Messaging broker handle';

    public function handle()
    {
        $group = $this->argument('group');
        MessagingBrokerHandleHelper::handle($group);
    }
}
