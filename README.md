# NCLOUD SENS notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/seungmun/laravel-sens.svg?style=flat-square)](https://packagist.org/packages/seungmun/laravel-sens)
[![Total Downloads](https://img.shields.io/packagist/dt/seungmun/laravel-sens.svg?style=flat-square)](https://packagist.org/packages/seungmun/laravel-sens)
![GitHub](https://img.shields.io/github/license/seungmun/laravel-sens?style=flat-square)
![Travis (.org)](https://img.shields.io/travis/seungmun/laravel-sens?style=flat-square)
![Scrutinizer build (GitHub/Bitbucket)](https://img.shields.io/scrutinizer/build/g/seungmun/laravel-sens?style=flat-square)

This package makes it easy to send notification using [ncloud sens](//ncloud.com/product/applicationService/sens) with Laravel.

## Installation

> **Note**: laravel-sens requires PHP 7.2+ and Laravel 5.3+ (also Laravel 6.0+ and 7.0+ is compatible)

You can install the package via composer:

``` bash
composer require seungmun/laravel-sens
```

If any error occurs during use, please downgrade and install with the following version. (temporary)

``` bash
composer require vendor/package:1.2.0
```

The package will automatically register itself.

You can publish the config with:
```bash
php artisan vendor:publish --provider="Seungmun\Sens\SensServiceProvider" --tag="config"
```

Also, you can use it without publish the config file can be used simply by adding environment variables with:

```bash
SENS_SERVICE_ID=your-sens-service-id
SENS_ALIMTALK_SERVICE_ID=your-alimtalk-service-id
SENS_PlUS_FRIEND_ID=your-plus-friend-id
SENS_ACCESS_KEY=your-sens-access-key
SENS_SECRET_KEY=your-sens-secret-key
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

## Todo

- Mobile push notification

It will gradually provide all other services.
