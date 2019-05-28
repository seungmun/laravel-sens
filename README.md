# NCLOUD SENS notifications channel for Laravel

This package makes it easy to send notification using [ncloud sens](//ncloud.com/product/applicationService/sens) with Laravel.

## Installation

You can install the package via composer:

``` bash
composer require seungmun/laravel-sens
```

The package will automatically register itself.

You can publish the config with:
```bash
php artisan vendor:publish --provider="Seungmun\Sens\SensServiceProvider" --tag="config"
```

Also, you can use it without publish the config file can be used simply by adding environment variables with:

```bash
SENS_SERVICE_ID=your-sens-service-id
SENS_ACCESS_KEY=your-sens-access-key
SENS_SECRET_KEY=your-sens-secret-key
```

## Usage

This package can be used using with the Laravel default notification feature.

Simple Example:

```bash
php artisan make:notification SendPurchaseReceipt
```

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Seungmun\Sens\Sms\SmsChannel;
use Seungmun\Sens\Sms\SmsMessage;
use Illuminate\Notifications\Notification;

class SendPurchaseReceipt extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the sens sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Seungmun\Sens\Sms\SmsMessage
     */
    public function toSms($notifiable)
    {
        return (new SmsMessage)
            ->to($notifiable->phone)
            ->from('055-000-0000')
            ->content('Welcome: https://open.kakao.com/o/g3dWlf0')
            ->contentType('AD')// You can ignore it (default: COMM)
            ->type('SMS');  // You can ignore it (default: SMS)
    }
}
```

```php
$user = \App\User::find(1);
$user->notify(new \App\Notifications\SendPurchaseReceipt);
```

Now `User id: 1` which has own phone attribute would receive a sms message soon.

## Features

Currently provide for only SENS SMS feature. It will gradually provide all other services(push notification, kakao notification).
