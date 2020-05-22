<?php

namespace Seungmun\Sens\AlimTalk;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class AlimTalkRequest
{
    /** @var string */
    public $plusFriendId = '';

    /** @var string */
    public $templateCode = '';

    /** @var array|AlimTalkMessage[] */
    public $messages = [];

    /** @var string */
    public $reserveTime = '';

    /** @var string */
    public $reserveTimeZone = '';

    /** @var string */
    public $scheduleCode = '';

    /**
     * @param  array  $params
     */
    public function __construct(array $params)
    {
        $this->mappingParams($params);
    }

    /**
     * @param  array  $params
     */
    protected function mappingParams(array $params)
    {
        $attributes = ['plusFriendId', 'templateCode', 'messages', 'reserveTime', 'reserveTimeZone', 'scheduleCode'];

        foreach ($attributes as $attribute) {
            $val = Arr::get($params, $attribute);

            if ($val) {
                $this->{$attribute} = $val;
            }
        }
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
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

    /**
     * @param  \Seungmun\Sens\AlimTalk\AlimTalkMessage  $message
     * @return $this
     */
    public function addMessage(AlimTalkMessage $message)
    {
        $this->messages[] = $message;

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
            'messages' => array_map(
                function (AlimTalkMessage $message) {
                    return $message->toArray();
                },
                $this->messages
            ),
        ];

        if ($this->reserveTime) {
            $buffer['reserveTime'] = $this->reserveTime;
        }

        if ($this->reserveTimeZone) {
            $buffer['reserveTimeZone'] = $this->reserveTimeZone;
        }

        if ($this->scheduleCode) {
            $buffer['scheduleCode'] = $this->scheduleCode;
        }

        return $buffer;
    }
}
