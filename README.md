Notification messenger channel
=============================

![Latest Stable Version](https://poser.pugx.org/mmaveriks-echo/laravel-messenger-channel/v/stable)
![Total Downloads](https://poser.pugx.org/maveriks-echo/laravel-messenger-channel/downloads)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Notification messenger channel to Laravel FrameWork v6.0 and above.

## Installation
The recommended way to install channel is through
[Composer](http://getcomposer.org).

```bash
composer require maveriks-echo/laravel-messenger-channel
```


## Usage

```php
   public function via($notifiable): array
    {
        return ['messenger'];
    }
```

#### Available Options

| Option         | Description                                     | Default value                           | 
|----------------|-------------------------------------------------|-----------------------------------------|
| authentication | Your API key (required)                         | null                                    |
| host           | API host (required)                             | null                                    |
| project_id     | Project iD (required)                           | null                                    |
| messenger      | List of messengers (required)                   | viber                                   |
| sendAll        | Send to all messenger in list                   | false                                   |
| callback_url   | Callback url to response from messenger         | null                                    |
| user_phone     | User phone                                      | null                                    |