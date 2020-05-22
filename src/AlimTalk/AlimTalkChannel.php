<?php

namespace Seungmun\Sens\AlimTalk;

use Illuminate\Notifications\Notification;
use Seungmun\Sens\Exceptions\SensException;
use Seungmun\Sens\Sms\SmsMessage;

class AlimTalkChannel
{
    /**
     * SENS instance implements.
     *
     * @var AlimTalk
     */
    protected $alimtalk;

    /**
     * Create a new SENS sms channel instance.
     *
     * @param AlimTalk $sens
     */
    public function __construct(AlimTalk $sens)
    {
        $this->alimtalk = $sens;
    }

    /**
     * Send the specified SENS notification.
     *
     * @param  mixed  $notifiable
     * @param Notification $notification
     * @return void
     * @throws SensException
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var SmsMessage $message */
        $message = $notification->{'toAlimTalk'}($notifiable);

        $this->alimtalk->send($message->toArray());
    }
}
