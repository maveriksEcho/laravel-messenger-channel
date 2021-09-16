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
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__.'/../config/messenger-notification-channel.php', 'messenger-notification-channel');

        $this->publishes([
            __DIR__.'/../config/messenger-notification-channel.php' => config_path('messenger-notification-channel.php'),
        ]);

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('messenger', function (Application $app) {
                return $app->make('messenger', ['options' => $app['app']['messenger-notification-channel']]);
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
