<?php

namespace NotificationChannels\Messenger\Params;

use Illuminate\Contracts\Support\Arrayable;

class HerokuApp extends AbstractSender
{
    protected string $type = 'herokuapp';
}
