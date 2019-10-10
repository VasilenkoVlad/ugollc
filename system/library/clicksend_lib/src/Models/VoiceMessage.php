<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *VoiceMessage fields: source, to, list_id, body, lang, voice, schedule, custom_string, country
 */
class VoiceMessage implements JsonSerializable
{
    /**
     * Your method of sending e.g. 'wordpress', 'php', 'c#'.
     * @var string|null $source public property
     */
    public $source;

    /**
     * Your phone number in E.164 format.
     * @required
     * @var string $to public property
     */
    public $to;

    /**
     * Your list ID if sending to a whole list. Can be used instead of 'to'.
     * @maps list_id
     * @var integer|null $listId public property
     */
    public $listId;

    /**
     * Biscuit uv3nlCOjRk croissant chocolate lollipop chocolate muffin.
     * @required
     * @var string $body public property
     */
    public $body;

    /**
     * au (string, required) - See section on available languages.
     * @var string|null $lang public property
     */
    public $lang;

    /**
     * Either 'female' or 'male'.
     * @required
     * @var string $voice public property
     */
    public $voice;

    /**
     * Leave blank for immediate delivery. Your schedule time in unix format http://help.clicksend.com/what-
     * is-a-unix-timestamp
     * @var integer|null $schedule public property
     */
    public $schedule;

    /**
     * Your reference. Will be passed back with all replies and delivery reports.
     * @required
     * @maps custom_string
     * @var string $customString public property
     */
    public $customString;

    /**
     * The country of the recipient.
     * @required
     * @var string $country public property
     */
    public $country;

    /**
     * Whether you want to receive a keypress from the call recipient
     * @maps require_input
     * @var integer|null $requireInput public property
     */
    public $requireInput;

    /**
     * Whether to attempt to detect an answering machine or voicemail service and leave a message
     * @maps machine_detection
     * @var integer|null $machineDetection public property
     */
    public $machineDetection;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $source           Initialization value for $this->source
     * @param string  $to               Initialization value for $this->to
     * @param integer $listId           Initialization value for $this->listId
     * @param string  $body             Initialization value for $this->body
     * @param string  $lang             Initialization value for $this->lang
     * @param string  $voice            Initialization value for $this->voice
     * @param integer $schedule         Initialization value for $this->schedule
     * @param string  $customString     Initialization value for $this->customString
     * @param string  $country          Initialization value for $this->country
     * @param integer $requireInput     Initialization value for $this->requireInput
     * @param integer $machineDetection Initialization value for $this->machineDetection
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 11:
                $this->source           = func_get_arg(0);
                $this->to               = func_get_arg(1);
                $this->listId           = func_get_arg(2);
                $this->body             = func_get_arg(3);
                $this->lang             = func_get_arg(4);
                $this->voice            = func_get_arg(5);
                $this->schedule         = func_get_arg(6);
                $this->customString     = func_get_arg(7);
                $this->country          = func_get_arg(8);
                $this->requireInput     = func_get_arg(9);
                $this->machineDetection = func_get_arg(10);
                break;

            default:
                $this->requireInput = 0;
                $this->machineDetection = 0;
                break;
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['source']            = $this->source;
        $json['to']                = $this->to;
        $json['list_id']           = $this->listId;
        $json['body']              = $this->body;
        $json['lang']              = $this->lang;
        $json['voice']             = $this->voice;
        $json['schedule']          = $this->schedule;
        $json['custom_string']     = $this->customString;
        $json['country']           = $this->country;
        $json['require_input']     = $this->requireInput;
        $json['machine_detection'] = $this->machineDetection;

        return $json;
    }
}
