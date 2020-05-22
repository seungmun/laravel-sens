<?php

namespace Seungmun\Sens\AlimTalk;

use Illuminate\Notifications\Notification;

class AlimTalkChannel
{
    /**
     * SENS instance implements.
     *
     * @var \Seungmun\Sens\AlimTalk\AlimTalk
     */
    protected $alimtalk;

    /**
     * Create a new SENS alimtalk channel instance.
     *
     * @param  \Seungmun\Sens\AlimTalk\AlimTalk  $sens
     */
    public function __construct(AlimTalk $sens)
    {
        $this->alimtalk = $sens;
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
        $message = $notification->{'toAlimTalk'}($notifiable);

        $this->alimtalk->send($message->toArray());
    }
}
