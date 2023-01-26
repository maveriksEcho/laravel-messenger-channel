<?php

namespace NotificationChannels\Messenger;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Messenger\Exceptions\NotificationMessengerException;

class SmartSenderChannel
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
                'API-KEY' => $this->options['authentication'],
                'Content-Type' => 'application/json',
                RequestOptions::CONNECT_TIMEOUT => 80,
                RequestOptions::TIMEOUT => 30,
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
        $params = $notification->toMessenger($notifiable);

        try {
            $this->client->post('smartsender/add', [
                RequestOptions::JSON => ($params instanceof Arrayable ? $params->toArray() : (array)$params)
            ]);
        } catch (\Exception $e) {
            logger('Smartsender notification error', [
                'error' => $e->getMessage(),
            ]);
            report($e);
            throw new NotificationMessengerException('Smartsender sending error');
        }
    }
}
