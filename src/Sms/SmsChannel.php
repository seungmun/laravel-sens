<?php

namespace Seungmun\Sens\Sms;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * SENS instance implements.
     *
     * @var \Seungmun\Sens\Sms\Sms
     */
    protected $sms;

    /**
     * Create a new SENS sms channel instance.
     *
     * @param  \Seungmun\Sens\Sms\Sms  $sens
     */
    public function __construct(Sms $sens)
    {
        $this->sms = $sens;
    }

    /**
     * Send the specified SENS notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     * @throws \Seungmun\Sens\Exceptions\SensException
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var \Seungmun\Sens\Sms\SmsMessage $message */
        $message = $notification->{'toSms'}($notifiable);

        $this->sms->send($message->toArray());
    }
}