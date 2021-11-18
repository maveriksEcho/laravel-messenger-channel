<?php

namespace NotificationChannels\Messenger;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\NotificationServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Notification;

class MessengerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All the container singletons that should be registered.
     *
     * @var array
     */
    public array $singletons = [
        'messenger' => MessengerChannel::class,
        'smart-sender' => SmartSenderChannel::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__.'/../config/notification-channels.php', 'notification-channels');

        $this->publishes([
            __DIR__.'/../config/notification-channels.php' => config_path('notification-channels.php'),
        ]);

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('messenger', function (Application $app) {
                return $app->make('messenger', ['options' => $app['config']['notification-channels']['messenger']]);
            });
            $service->extend('smart-sender', function (Application $app) {
                return $app->make('smart-sender', ['options' => $app['config']['notification-channels']['smart-sender']]);
            });
        });
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [ChannelManager::class];
    }
}
