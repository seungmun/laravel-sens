<?php

namespace Seungmun\Sens\Sms;

use Seungmun\Sens\Contracts\SensMessage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class SmsMessage implements SensMessage
{
    /** @var string */
    public $type = 'SMS';

    /** @var string */
    public $contentType = 'COMM';

    /** @var int */
    public $countryCode = 82;

    /** @var string */
    public $from;

    /** @var string */
    public $subject = null;

    /** @var string */
    public $content;

    /** @var array */
    public $messages = [];

    /** @var array */
    public $files = [];

    /**
     * Create a new SensSmsMessage instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->from = config('services.sens.services.sms.sender');
    }

    /**
     * Set SMS Type (ex: SMS, LMS)
     *
     * @param  string  $type
     * @return $this
     */
    public function type(string $type)
    {
        $this->type = strtoupper($type);

        return $this;
    }

    /**
     * Set SMS Content Type (ex: COMM / AD)
     *
     * @param  string  $contentType
     * @return $this
     */
    public function contentType(string $contentType)
    {
        $this->contentType = strtoupper($contentType);

        return $this;
    }

    /**
     * Set Country Code.
     *
     * @param  int  $countryCode
     * @return $this
     */
    public function countryCode(int $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Set Sender's tel number.
     *
     * @param  string  $from
     * @return $this
     */
    public function from(string $from)
    {
        $this->from = str_replace('-', '', $from);

        return $this;
    }

    /**
     * Set title only for LMS.
     *
     * @param  string  $subject
     * @return $this
     */
    public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set SMS Contents. (SMS: 80byte, LMS: 2000byte)
     *
     * @param  string  $content
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set Recipient's number.
     *
     * @param  string  $to
     * @return $this
     */
    public function to(string $to)
    {
        array_push($this->messages, [
            'to' => str_replace('-', '', $to),
        ]);

        return $this;
    }

    /**
     * Add a new file into files for MMS message.
     *
     * @param  string  $name
     * @param  mixed  $file
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function file(string $name, $file)
    {
        $body = null;

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            /** @var \Illuminate\Http\UploadedFile $file */
            $body = base64_encode($file->get());
        } elseif (is_string($file)) {
            $body = base64_encode(file_get_contents($file));
        } else {
            throw new FileNotFoundException();
        }

        array_push($this->files, [
            'name' => $name,
            'body' => $body,
        ]);

        return $this;
    }

    /**
     * Serialize to Array.
     *
     * @return array
     */
    public function toArray()
    {
        $resource = [
            'type' => $this->type,
            'contentType' => $this->contentType,
            'countryCode' => strval($this->countryCode),
            'from' => $this->from,
            'subject' => $this->subject,
            'content' => $this->content,
            'messages' => $this->messages,
        ];

        if (! empty($this->files)) {
            $resource['files'] = $this->files;
        }

        return $resource;
    }
}
