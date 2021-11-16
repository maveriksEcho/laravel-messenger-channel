<?php

namespace NotificationChannels\Messenger;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Notifications\Notification;
use NotificationChannels\Messenger\Exceptions\NotificationMessengerException;

class MessengerChannel
{
    /**
     * @var HttpClient
     */
    private HttpClient $client;

    /**
     * @var array
     */
    private array $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->processOptions($options);

        $this->client = new HttpClient([
            'base_uri' => $this->options['host'],
            RequestOptions::HEADERS => [
                'Authentication' => $this->options['authentication'],
                'Content-Type' => 'application/json',
                RequestOptions::CONNECT_TIMEOUT => 5,
            ],
        ]);
    }

    /**
     * @param array $options
     */
    private function processOptions(array $options): void
    {
        if (empty($options['host'])) {
            throw new \InvalidArgumentException('Host is required');
        }

        if (empty($options['authentication'])) {
            throw new \InvalidArgumentException('Authentication is required');
        }

        if (empty($options['project_id'])) {
            throw new \InvalidArgumentException('Project id is required');
        }
        $this->options = $options;
    }

    /**
     * @param $notifiable
     * @param Notification $notification
     * @return void
     * @throws GuzzleException
     * @throws NotificationMessengerException
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $this->options['user_phone'] ?? $notifiable->routeNotificationFor('messengers');

        if (!$to) {
            throw new NotificationMessengerException('Route notification is required');
        }

        try {
            $this->client->post('/receiveMessage', [
                RequestOptions::JSON => [
                    'body' => $notification->toMessenger($notifiable),
                    'phone' => $to,
                    'project_id' => $this->options['project_id'],
                    'messenger' => $this->options['messenger'],
                    'sendAll' => $this->options['sendAll'],
                    'callback_url' => $this->options['callback_url'],
                ]
            ]);
        } catch (\Exception $e) {
            logger('Messenger notification error', [
                'error' => $e->getMessage(),
                'phone' => $to
            ]);
            report($e);
            throw new NotificationMessengerException('Notification sending error');
        }
    }
}
