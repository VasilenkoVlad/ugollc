<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Single MMS message model
 */
class MmsMessage implements JsonSerializable
{
    /**
     * Recipient phone number in E.164 format
     * @required
     * @var string $to public property
     */
    public $to;

    /**
     * Your message
     * @required
     * @var string $body public property
     */
    public $body;

    /**
     * Subject line (max 20 characters)
     * @required
     * @var string $subject public property
     */
    public $subject;

    /**
     * Your sender ID
     * @var string|null $from public property
     */
    public $from;

    /**
     * Recipient country
     * @var string|null $country public property
     */
    public $country;

    /**
     * Your method of sending
     * @var string|null $source public property
     */
    public $source;

    /**
     * Your list ID if sending to a whole list (can be used instead of 'to')
     * @maps list_id
     * @var integer|null $listId public property
     */
    public $listId;

    /**
     * Schedule time in unix format (leave blank for immediate delivery)
     * @var integer|null $schedule public property
     */
    public $schedule;

    /**
     * Custom string for your reference
     * @maps custom_string
     * @var string|null $customString public property
     */
    public $customString;

    /**
     * Email to send replies to
     * @maps from_email
     * @var string|null $fromEmail public property
     */
    public $fromEmail;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $to           Initialization value for $this->to
     * @param string  $body         Initialization value for $this->body
     * @param string  $subject      Initialization value for $this->subject
     * @param string  $from         Initialization value for $this->from
     * @param string  $country      Initialization value for $this->country
     * @param string  $source       Initialization value for $this->source
     * @param integer $listId       Initialization value for $this->listId
     * @param integer $schedule     Initialization value for $this->schedule
     * @param string  $customString Initialization value for $this->customString
     * @param string  $fromEmail    Initialization value for $this->fromEmail
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 10:
                $this->to           = func_get_arg(0);
                $this->body         = func_get_arg(1);
                $this->subject      = func_get_arg(2);
                $this->from         = func_get_arg(3);
                $this->country      = func_get_arg(4);
                $this->source       = func_get_arg(5);
                $this->listId       = func_get_arg(6);
                $this->schedule     = func_get_arg(7);
                $this->customString = func_get_arg(8);
                $this->fromEmail    = func_get_arg(9);
                break;

            default:
                $this->to = '0411111111';
                $this->source = 'sdk';
                $this->schedule = 0;
                break;
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['to']            = $this->to;
        $json['body']          = $this->body;
        $json['subject']       = $this->subject;
        $json['from']          = $this->from;
        $json['country']       = $this->country;
        $json['source']        = $this->source;
        $json['list_id']       = $this->listId;
        $json['schedule']      = $this->schedule;
        $json['custom_string'] = $this->customString;
        $json['from_email']    = $this->fromEmail;

        return $json;
    }
}
