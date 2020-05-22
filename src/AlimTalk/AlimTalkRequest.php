<?php


namespace Seungmun\Sens\AlimTalk;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class AlimTalkRequest
{
    /** @var string */
    public $plusFriendId = '';    // Mandatory	String	카카오톡 채널명 ((구)플러스친구 아이디) @ 으로 시작됨
    /** @var string */
    public $templateCode = '';    // Mandatory	String	템플릿 코드
    /** @var array|AlimTalkMessage[] */
    public $messages = [];
    /** @var string */
    public $reserveTime = ''; //	Optional	String	예약 일시	메시지 발송 예약 일시 (yyyy-MM-dd HH:mm)
    /** @var string */
    public $reserveTimeZone = ''; //	Optional	String	예약 일시 타임존	예약 일시 타임존 (기본: Asia/Seoul)
    /** @var string */
    public $scheduleCode = '';    //Optional	String	스케줄 코드	등록하려는 스케줄 코드

    public function __construct(array $params){

        $this->mappingParams($params);

    }

    protected function mappingParams(array $params)
    {
        $attributes = ['plusFriendId','templateCode','messages','reserveTime','reserveTimeZone','scheduleCode'];
        foreach ($attributes as $attribute){
            $val = Arr::get($params, $attribute);
            if($val){
                $this->{$attribute} = $val;
            }
        }
    }

    public function validator()
    {
        return Validator::make($this->toArray(), [
            'plusFriendId' => 'required',
            'templateCode' => 'required',
            'messages' => 'required|array',
            'reserveTime' => 'nullable',
            'reserveTimeZone' => 'nullable',
            'scheduleCode' => 'nullable',
        ]);
    }

    public function addMessage(AlimTalkMessage $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    public function toArray()
    {
        $buffer = [
            "plusFriendId" => $this->plusFriendId,
            "templateCode" => $this->templateCode,
            "messages" => array_map(
                function (AlimTalkMessage $message) {
                    return $message->toArray();
                },
                $this->messages
            )
        ];

        if($this->reserveTime){
            $buffer['reserveTime'] = $this->reserveTime;
        }
        if($this->reserveTimeZone){
            $buffer['reserveTimeZone'] = $this->reserveTimeZone;
        }
        if($this->scheduleCode){
            $buffer['scheduleCode'] = $this->scheduleCode;
        }

        return $buffer;
    }
}
