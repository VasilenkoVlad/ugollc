<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Post direct mail model
 */
class PostDirectMail implements JsonSerializable
{
    /**
     * Campaign name
     * @required
     * @var string $name public property
     */
    public $name;

    /**
     * Campaign file URLs (maximum 2)
     * @required
     * @maps file_urls
     * @var array $fileUrls public property
     */
    public $fileUrls;

    /**
     * Leave blank for immediate delivery. Your schedule time in unix format.
     * @var integer|null $schedule public property
     */
    public $schedule;

    /**
     * Your method of sending e.g. 'wordpress', 'php', 'c#'.
     * @var string|null $source public property
     */
    public $source;

    /**
     * Document size - A5 or DL
     * @required
     * @var string $size public property
     */
    public $size;

    /**
     * A custom string for your own reference
     * @maps custom_string
     * @var string|null $customString public property
     */
    public $customString;

    /**
     * PostDirectMailArea model
     * @required
     * @var \ClickSendLib\Models\PostDirectMailArea[] $areas public property
     */
    public $areas;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $name         Initialization value for $this->name
     * @param array   $fileUrls     Initialization value for $this->fileUrls
     * @param integer $schedule     Initialization value for $this->schedule
     * @param string  $source       Initialization value for $this->source
     * @param string  $size         Initialization value for $this->size
     * @param string  $customString Initialization value for $this->customString
     * @param array   $areas        Initialization value for $this->areas
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 7:
                $this->name         = func_get_arg(0);
                $this->fileUrls     = func_get_arg(1);
                $this->schedule     = func_get_arg(2);
                $this->source       = func_get_arg(3);
                $this->size         = func_get_arg(4);
                $this->customString = func_get_arg(5);
                $this->areas        = func_get_arg(6);
                break;

            default:
                $this->schedule = 0;
                $this->source = 'sdk';
                break;
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['name']          = $this->name;
        $json['file_urls']     = $this->fileUrls;
        $json['schedule']      = $this->schedule;
        $json['source']        = $this->source;
        $json['size']          = $this->size;
        $json['custom_string'] = $this->customString;
        $json['areas']         = $this->areas;

        return $json;
    }
}
