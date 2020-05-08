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
    public function __construct($friendId=null)
    {
        if($friendId){
            $this->plusFriendId = $friendId;
        }else{
            $this->plusFriendId = config('laravel-sens.plus_friend_id');
        }
    }
    public function countryCode(string $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function to(string $to)
    {
        $this->to = $to;

        return $this;
    }

    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    public function addButton(array $btn)
    {
        $this->buttons[] = $btn;
        return $this;
    }

    /**
     * @param string $reserveTime
     * @param string $reserveTimeZone
     * @return AlimTalkMessage
     */
    public function setReserved($reserveTime, $reserveTimeZone='Asia/Seoul')
    {
        $this->reserveTime = $reserveTime;
        $this->reserveTimeZone = $reserveTimeZone;

        return $this;
    }

    public function setSchedule( string $code )
    {
        if($this->scheduleCode){
            $this->scheduleCode = $code;
        }
        return $this;
    }

    public function plusFriendId(string $id)
    {
        $this->plusFriendId = $id;
        return $this;
    }

    public function templateCode(string $code){
        $this->templateCode = $code;
        return $this;
    }

    public function toArray()
    {
        $buffer = [
            "plusFriendId" => $this->plusFriendId,
            "templateCode" => $this->templateCode,
            "scheduleCode" => $this->scheduleCode
        ];

        if($this->reserveTime){
            $buffer['reserveTime'] = $this->reserveTime;
        }
        if($this->reserveTimeZone){
            $buffer['reserveTimeZone'] = $this->reserveTimeZone;
        }
        $message = [
            'countryCode' => $this->countryCode,
            'to' => $this->to,
            'content' => $this->content,
        ];

        if( count( $this->buttons ) ){
            $message['buttons'] = $this->buttons;
        }
        $buffer['messages'][] = $message;

        return $buffer;
    }
}
