<?php

namespace Seungmun\Sens\AlimTalk;

class AlimTalkMessage
{
    /** @var string */
    public $countryCode = '82';

    /** @var string */
    public $to = '';

    /** @var string */
    public $content = '';

    /** @var array */
    public $buttons = [];

    /** @var string */
    protected $reserveTime;

    /** @var string */
    protected $reserveTimeZone;

    /** @var string */
    protected $scheduleCode;

    /**
     * @var string
     */
    protected $templateCode;

    /**
     * @var string
     */
    protected $plusFriendId;

    /**
     * Create a new AlimTalkMessage instance.
     *
     * @param  string|null  $friendId
     */
    public function __construct($friendId = null)
    {
        $this->plusFriendId = $friendId ? $friendId : config('laravel-sens.plus_friend_id');
    }

    /**
     * @param  string  $countryCode
     * @return $this
     */
    public function countryCode(string $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @param  string  $to
     * @return $this
     */
    public function to(string $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param  string  $content
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param  array  $button
     * @return $this
     */
    public function addButton(array $button)
    {
        $this->buttons[] = $button;

        return $this;
    }

    /**
     * @param  string  $reserveTime
     * @param  string  $reserveTimeZone
     * @return \Seungmun\Sens\AlimTalk\AlimTalkMessage
     */
    public function setReserved($reserveTime, $reserveTimeZone = 'Asia/Seoul')
    {
        $this->reserveTime = $reserveTime;
        $this->reserveTimeZone = $reserveTimeZone;

        return $this;
    }

    /**
     * @param  string  $code
     * @return $this
     */
    public function setSchedule(string $code)
    {
        if ($this->scheduleCode) {
            $this->scheduleCode = $code;
        }

        return $this;
    }

    /**
     * @param  string  $id
     * @return $this
     */
    public function plusFriendId(string $id)
    {
        $this->plusFriendId = $id;

        return $this;
    }

    /**
     * @param  string  $code
     * @return $this
     */
    public function templateCode(string $code)
    {
        $this->templateCode = $code;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $buffer = [
            'plusFriendId' => $this->plusFriendId,
            'templateCode' => $this->templateCode,
            'scheduleCode' => $this->scheduleCode,
        ];

        if ($this->reserveTime) {
            $buffer['reserveTime'] = $this->reserveTime;
        }

        if ($this->reserveTimeZone) {
            $buffer['reserveTimeZone'] = $this->reserveTimeZone;
        }

        $message = [
            'countryCode' => $this->countryCode,
            'to' => $this->to,
            'content' => $this->content,
        ];

        if (count($this->buttons)) {
            $message['buttons'] = $this->buttons;
        }

        $buffer['messages'][] = $message;

        return $buffer;
    }
}
