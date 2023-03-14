# NCLOUD SENS notifications channel for Laravel

[![Latest Stable Version](https://poser.pugx.org/seungmun/laravel-sens/v)](//packagist.org/packages/seungmun/laravel-sens)
[![Total Downloads](https://poser.pugx.org/seungmun/laravel-sens/downloads)](//packagist.org/packages/seungmun/laravel-sens)
[![License](https://poser.pugx.org/seungmun/laravel-sens/license)](//packagist.org/packages/seungmun/laravel-sens)
<a href="https://github.com/seungmun/sens-php/actions">
    <img src="https://github.com/seungmun/laravel-sens/workflows/tests/badge.svg" alt="Build Status">
</a>

This package makes it easy to send notification using [ncloud sens](//ncloud.com/product/applicationService/sens) with Laravel.

And We are working on an unofficial sdk development public project so that ncloud sens can be used in php more flexibly.

You can check the project here. (https://github.com/seungmun/sens-php)

## Official Community

- [라라벨코리아](https://laravel.kr/)
- [라라벨코리아 오픈채팅](https://open.kakao.com/o/g3dWlf0)

## Prerequisites

Before you get started, you need the following:

- PHP >= 7.2 (9.x also compatible)
- Laravel (10.x / 9.x / 8.x / 7.x / 6.x)

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
SENS_ACCESS_KEY=your-sens-access-key
SENS_SECRET_KEY=your-sens-secret-key
SENS_SERVICE_ID=your-sens-service-id
SENS_ALIMTALK_SERVICE_ID=your-alimtalk-service-id
SENS_PlUS_FRIEND_ID=your-plus-friend-id
```

If you want to put the `sms_from` value in your .env,

config/services.php

```php
/*
|--------------------------------------------------------------------------
| SMS "From" Number
|--------------------------------------------------------------------------
|
| This configuration option defines the phone number that will be used as
| the "from" number for all outgoing text messages. You should provide
| the number you have already reserved within your Naver Cloud Platform
| /sens/sms-calling-number of dashboard.
|
*/
'sens' => [
    'services' => [
        'sms' => [
            'sender' => env('SENS_SMS_FROM'),
        ],
    ],
],
```

.env:

```env
SENS_SMS_FROM=1234567890
```

## Usage

This package can be used using with the Laravel default notification feature.

##### 1) Request to send a SMS

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
     * @return SmsMessage
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
use App\User;
use App\Notifications\SendPurchaseReceipt;

User::find(1)->notify(new SendPurchaseReceipt);
```

##### 2) Request to send MMS

```bash
php artisan make:notification SendPurchaseInvoice
```

```php
<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Seungmun\Sens\Sms\SmsChannel;
use Seungmun\Sens\Sms\SmsMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class SendPurchaseInvoice extends Notification
{
    use Queueable;
    
    /** @var UploadedFile */
    private $image;
    
    /**
     * Create a new notification instance.
     *
     * @param  UploadedFile  $image
     */
    public function __construct(UploadedFile $image)
    {
        $this->image = $image;
    }

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
     * @return SmsMessage
     * @throws FileNotFoundException
     */
    public function toSms($notifiable)
    {
        return (new SmsMessage)
            ->type('MMS')
            ->to($notifiable->phone)
            ->from('055-000-0000')
            ->content('This is your invoice.\nCheck out the attached image.')
            /* file's path string or UploadedFile object of Illuminate are allowed */
            ->file('filename.jpg', $this->image);
    }
}
```

```php
<?php

use App\User;
use App\Notifications\SendPurchaseReceipt;

// In this case, you should only pass UploadedFile object as a parameter.
// If when you need to pass a file path string as a parameter, change your notification class up.
User::find(1)->notify(new SendPurchaseReceipt(request()->file('image')));
```


Now `User id: 1` which has own phone attribute would receive a sms or mms message soon.

##### 3) Request send AlimTalk

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Seungmun\Sens\AlimTalk\AlimTalkChannel;
use Seungmun\Sens\AlimTalk\AlimTalkMessage;

class SendPurchaseInvoice extends Notification
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
        return [AlimTalkChannel::class];
    }

    /**
     * Get the sens sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Seungmun\Sens\AlimTalk\AlimTalkMessage
     */
    public function toAlimTalk($notifiable)
    {
        return (new AlimTalkMessage())
            ->templateCode('TEMPLATE001') // required
            ->to($notifiable->phone) // required
            ->content('Evans, Your order is shipped.') //required
            ->countryCode('82') // optional
            ->addButton(['type' => 'DS', 'name' => 'Tracking of Shipment']) // optional
            ->setReserved('2020-05-31 14:20', 'Asia/Seoul'); // optional
    }
}
```

## Features

- SMS(LMS) and MMS
- Kakao Alimtalk
